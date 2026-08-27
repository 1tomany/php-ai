<?php

namespace OneToMany\AI\Bridge\Gemini;

use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Document as DocumentRecord;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\DocumentList;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\FileSearchStore as FileSearchStoreRecord;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\ImportFileResponse;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Operation;
use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;
use OneToMany\AI\Resource\SearchStore\Statistics;

use function array_key_exists;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function max;
use function sprintf;
use function usleep;

final readonly class SearchStoreProvider extends AbstractProvider implements SearchStoreProviderInterface
{
    private const string SOURCE_FILE_METADATA_KEY = '__onetomany_ai_file_id';
    private const int IMPORT_POLL_INTERVAL_MICROSECONDS = 250_000;
    private const int IMPORT_POLL_LIMIT = 240;

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function create(string $name, ?string $description = null): SearchStore
    {
        $response = $this->transport->postRequest($this->url($this->apiVersion, 'fileSearchStores'), [
            'headers' => $this->headers(),
            'json' => ['displayName' => $name],
        ]);

        return $this->mapSearchStore($this->transport->decode($response, FileSearchStoreRecord::class));
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function read(string $searchStoreId): SearchStore
    {
        $response = $this->transport->getRequest($this->url($this->apiVersion, $searchStoreId), [
            'headers' => $this->headers(),
        ]);

        return $this->mapSearchStore($this->transport->decode($response, FileSearchStoreRecord::class));
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function delete(string $searchStoreId): void
    {
        $this->transport->deleteRequest($this->url($this->apiVersion, $searchStoreId), [
            'headers' => $this->headers(),
            'query' => ['force' => 'true'],
        ]);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     *
     * @param array<string, string|int|float|bool> $metadata
     */
    #[\Override]
    public function attachFile(
        string $searchStoreId,
        string $fileId,
        array $metadata = [],
        bool $force = false,
    ): SearchStoreFile {
        if (!$force && null !== $record = $this->findFile($searchStoreId, $fileId)) {
            return $this->mapSearchStoreFile($record, $searchStoreId, $fileId);
        }

        $response = $this->transport->postRequest($this->url($this->apiVersion, $searchStoreId.':importFile'), [
            'headers' => $this->headers(),
            'json' => [
                'fileName' => $fileId,
                'customMetadata' => $this->normalizeMetadata($fileId, $metadata),
            ],
        ]);

        $operation = $this->transport->decode($response, Operation::class);
        $result = $this->waitForImport($operation);

        if (null === $result->documentName) {
            throw new RuntimeException('Gemini did not return the attached search store file ID.');
        }

        $record = $this->getFile($result->documentName);

        return $this->mapSearchStoreFile($record, $searchStoreId, $fileId, $metadata);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function readFile(string $searchStoreId, string $searchStoreFileId): SearchStoreFile
    {
        $record = $this->getFile($searchStoreFileId);
        $metadata = $this->metadata($record);
        $fileId = $metadata[self::SOURCE_FILE_METADATA_KEY] ?? $record->name;

        if (!is_string($fileId) || '' === $fileId) {
            $fileId = $record->name;
        }

        return $this->mapSearchStoreFile($record, $searchStoreId, $fileId, $metadata);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function deleteFile(string $searchStoreId, string $searchStoreFileId): void
    {
        $this->transport->deleteRequest($this->url($this->apiVersion, $searchStoreFileId), [
            'headers' => $this->headers(),
            'query' => ['force' => 'true'],
        ]);
    }

    private function findFile(string $searchStoreId, string $fileId): ?DocumentRecord
    {
        $pageToken = null;

        do {
            $query = ['pageSize' => 20];

            if (null !== $pageToken) {
                $query['pageToken'] = $pageToken;
            }

            $response = $this->transport->getRequest($this->url($this->apiVersion, $searchStoreId, 'documents'), [
                'headers' => $this->headers(),
                'query' => $query,
            ]);

            $page = $this->transport->decode($response, DocumentList::class);

            foreach ($page->documents as $document) {
                $record = $this->createDocumentRecord($document);

                if (null !== $record && $fileId === ($this->metadata($record)[self::SOURCE_FILE_METADATA_KEY] ?? null)) {
                    return $record;
                }
            }

            $pageToken = $page->nextPageToken;
        } while (null !== $pageToken && '' !== $pageToken);

        return null;
    }

    private function getFile(string $searchStoreFileId): DocumentRecord
    {
        $response = $this->transport->getRequest($this->url($this->apiVersion, $searchStoreFileId), [
            'headers' => $this->headers(),
        ]);

        return $this->transport->decode($response, DocumentRecord::class);
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

    private function mapSearchStore(FileSearchStoreRecord $record): SearchStore
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

        return new SearchStore(
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
     * @param non-empty-string $searchStoreId
     * @param non-empty-string $fileId
     * @param ?array<string, string|int|float|bool> $metadata
     */
    private function mapSearchStoreFile(
        DocumentRecord $record,
        string $searchStoreId,
        string $fileId,
        ?array $metadata = null,
    ): SearchStoreFile {
        $metadata ??= $this->metadata($record);
        unset($metadata[self::SOURCE_FILE_METADATA_KEY]);

        return new SearchStoreFile(
            $record->name,
            $searchStoreId,
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
    private function metadata(DocumentRecord $record): array
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
    private function createDocumentRecord(array $document): ?DocumentRecord
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

        return new DocumentRecord(
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
