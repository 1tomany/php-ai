<?php

namespace OneToMany\AI\Bridge\Gemini;

use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\FileSearchStore as FileSearchStoreRecord;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\ImportFileResponse;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Operation;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\SearchIndexFile as SearchIndexFileRecord;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\SearchIndexFileList;
use OneToMany\AI\Contract\Bridge\SearchIndexProviderInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Resource\SearchIndex\SearchIndex;
use OneToMany\AI\Resource\SearchIndex\SearchIndexFile;
use OneToMany\AI\Resource\SearchIndex\Statistics;

use function array_key_exists;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function max;
use function sprintf;
use function usleep;

final readonly class SearchIndexProvider extends AbstractProvider implements SearchIndexProviderInterface
{
    private const string SOURCE_FILE_METADATA_KEY = '__onetomany_ai_file_id';
    private const int IMPORT_POLL_INTERVAL_MICROSECONDS = 250_000;
    private const int IMPORT_POLL_LIMIT = 240;

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchIndexProviderInterface
     */
    #[\Override]
    public function create(string $name, ?string $description = null): SearchIndex
    {
        $response = $this->transport->postRequest($this->url($this->apiVersion, 'fileSearchStores'), [
            'headers' => $this->headers(),
            'json' => ['displayName' => $name],
        ]);

        return $this->mapSearchIndex($this->transport->decode($response, FileSearchStoreRecord::class));
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchIndexProviderInterface
     *
     * @param array<string, string|int|float|bool> $metadata
     */
    #[\Override]
    public function attachFile(
        string $searchIndexId,
        string $fileId,
        array $metadata = [],
        bool $force = false,
    ): SearchIndexFile {
        if (!$force && null !== $record = $this->findFile($searchIndexId, $fileId)) {
            return $this->mapSearchIndexFile($record, $searchIndexId, $fileId);
        }

        $response = $this->transport->postRequest($this->url($this->apiVersion, $searchIndexId.':importFile'), [
            'headers' => $this->headers(),
            'json' => [
                'fileName' => $fileId,
                'customMetadata' => $this->normalizeMetadata($fileId, $metadata),
            ],
        ]);

        $operation = $this->transport->decode($response, Operation::class);
        $result = $this->waitForImport($operation);

        if (null === $result->documentName) {
            throw new RuntimeException('Gemini did not return the imported search index file ID.');
        }

        $response = $this->transport->getRequest($this->url($this->apiVersion, $result->documentName), [
            'headers' => $this->headers(),
        ]);

        $record = $this->transport->decode($response, SearchIndexFileRecord::class);

        return $this->mapSearchIndexFile($record, $searchIndexId, $fileId, $metadata);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchIndexProviderInterface
     */
    #[\Override]
    public function removeFile(string $searchIndexId, string $searchIndexFileId): void
    {
        $this->transport->deleteRequest($this->url($this->apiVersion, $searchIndexFileId), [
            'headers' => $this->headers(),
            'query' => ['force' => 'true'],
        ]);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchIndexProviderInterface
     */
    #[\Override]
    public function read(string $searchIndexId): SearchIndex
    {
        $response = $this->transport->getRequest($this->url($this->apiVersion, $searchIndexId), [
            'headers' => $this->headers(),
        ]);

        return $this->mapSearchIndex($this->transport->decode($response, FileSearchStoreRecord::class));
    }

    private function findFile(string $searchIndexId, string $fileId): ?SearchIndexFileRecord
    {
        $pageToken = null;

        do {
            $query = ['pageSize' => 20];

            if (null !== $pageToken) {
                $query['pageToken'] = $pageToken;
            }

            $response = $this->transport->getRequest($this->url($this->apiVersion, $searchIndexId, 'documents'), [
                'headers' => $this->headers(),
                'query' => $query,
            ]);

            $page = $this->transport->decode($response, SearchIndexFileList::class);

            foreach ($page->documents as $document) {
                $record = $this->createSearchIndexFileRecord($document);

                if (null !== $record && $fileId === ($this->metadata($record)[self::SOURCE_FILE_METADATA_KEY] ?? null)) {
                    return $record;
                }
            }

            $pageToken = $page->nextPageToken;
        } while (null !== $pageToken && '' !== $pageToken);

        return null;
    }

    private function waitForImport(Operation $operation): ImportFileResponse
    {
        for ($attempt = 0; $attempt < self::IMPORT_POLL_LIMIT; ++$attempt) {
            if ($operation->done) {
                if (null !== $operation->error) {
                    throw new RuntimeException($operation->error->message ?? sprintf('The Gemini import operation failed with code %s.', (string) $operation->error->code));
                }

                if (null === $operation->response) {
                    throw new RuntimeException('Gemini did not return a result for the import operation.');
                }

                return $operation->response;
            }

            if ($attempt > 0) {
                usleep(self::IMPORT_POLL_INTERVAL_MICROSECONDS);
            }

            $response = $this->transport->getRequest($this->url($this->apiVersion, $operation->name), [
                'headers' => $this->headers(),
            ]);

            $operation = $this->transport->decode($response, Operation::class);
        }

        throw new RuntimeException('The Gemini import operation did not complete in time.');
    }

    private function mapSearchIndex(FileSearchStoreRecord $record): SearchIndex
    {
        $completed = max(0, (int) $record->activeDocumentsCount);
        $inProgress = max(0, (int) $record->pendingDocumentsCount);
        $failed = max(0, (int) $record->failedDocumentsCount);

        if ($inProgress > 0) {
            $status = 'in_progress';
        } elseif ($failed > 0) {
            $status = 'failed';
        } else {
            $status = 'completed';
        }

        return new SearchIndex(
            $record->name,
            '' !== $record->displayName ? $record->displayName : $record->name,
            status: $status,
            statistics: new Statistics(
                $completed + $inProgress + $failed,
                $completed,
                $inProgress,
                $failed,
            ),
        );
    }

    /**
     * @param non-empty-string $searchIndexId
     * @param non-empty-string $fileId
     * @param ?array<string, string|int|float|bool> $metadata
     */
    private function mapSearchIndexFile(
        SearchIndexFileRecord $record,
        string $searchIndexId,
        string $fileId,
        ?array $metadata = null,
    ): SearchIndexFile {
        $metadata ??= $this->metadata($record);
        unset($metadata[self::SOURCE_FILE_METADATA_KEY]);

        return new SearchIndexFile(
            $record->name,
            $searchIndexId,
            $fileId,
            match ($record->state) {
                'STATE_ACTIVE' => 'completed',
                'STATE_FAILED' => 'failed',
                default => 'in_progress',
            },
            $metadata,
        );
    }

    /**
     * @param non-empty-string $fileId
     * @param array<string, string|int|float|bool> $metadata
     *
     * @return non-empty-list<array{key: string, stringValue?: string, numericValue?: int|float}>
     */
    private function normalizeMetadata(string $fileId, array $metadata): array
    {
        $metadata[self::SOURCE_FILE_METADATA_KEY] = $fileId;
        $normalized = [];

        foreach ($metadata as $key => $value) {
            if (is_int($value) || is_float($value)) {
                $normalized[] = ['key' => $key, 'numericValue' => $value];
            } else {
                $normalized[] = [
                    'key' => $key,
                    'stringValue' => is_bool($value) ? ($value ? 'true' : 'false') : $value,
                ];
            }
        }

        return $normalized;
    }

    /**
     * @return array<string, string|int|float|bool>
     */
    private function metadata(SearchIndexFileRecord $record): array
    {
        $metadata = [];

        foreach ($record->customMetadata as $entry) {
            if (!isset($entry['key']) || !is_string($entry['key']) || '' === $entry['key']) {
                continue;
            }

            if (array_key_exists('stringValue', $entry) && is_string($entry['stringValue'])) {
                $metadata[$entry['key']] = $entry['stringValue'];
            } elseif (array_key_exists('numericValue', $entry) && (is_int($entry['numericValue']) || is_float($entry['numericValue']))) {
                $metadata[$entry['key']] = $entry['numericValue'];
            }
        }

        return $metadata;
    }

    /**
     * @param array<string, mixed> $document
     */
    private function createSearchIndexFileRecord(array $document): ?SearchIndexFileRecord
    {
        $name = $document['name'] ?? null;

        if (!is_string($name) || '' === $name) {
            return null;
        }

        $state = $document['state'] ?? 'STATE_UNSPECIFIED';

        if (!is_string($state) || '' === $state) {
            $state = 'STATE_UNSPECIFIED';
        }

        $customMetadata = [];

        if (is_array($document['customMetadata'] ?? null)) {
            foreach ($document['customMetadata'] as $entry) {
                if (is_array($entry)) {
                    $normalizedEntry = [];

                    foreach ($entry as $key => $value) {
                        if (is_string($key)) {
                            $normalizedEntry[$key] = $value;
                        }
                    }

                    $customMetadata[] = $normalizedEntry;
                }
            }
        }

        $displayName = $document['displayName'] ?? '';

        return new SearchIndexFileRecord(
            $name,
            $state,
            $customMetadata,
            is_string($displayName) ? $displayName : '',
        );
    }

    /**
     * @return array{x-goog-api-key: string}
     */
    private function headers(): array
    {
        return ['x-goog-api-key' => $this->apiKey];
    }
}
