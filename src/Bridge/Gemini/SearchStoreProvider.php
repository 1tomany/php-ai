<?php

namespace OneToMany\AI\Bridge\Gemini;

use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Document;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\DocumentList;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\FileSearchStore;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\ImportFileResponse;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Operation;
use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;

use function array_key_exists;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
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
    public function create(
        string $name,
        ?string $description = null,
    ): SearchStore {
        $url = $this->url($this->apiVersion, 'fileSearchStores');

        $response = $this->transport->postRequest($url, [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
            'json' => [
                'displayName' => $name,
            ],
        ]);

        return $this->transport->decode($response, FileSearchStore::class)->toResource();
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function read(string $searchStoreId): SearchStore
    {
        $url = $this->url($this->apiVersion, $searchStoreId);

        $response = $this->transport->getRequest($url, [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
        ]);

        return $this->transport->decode($response, FileSearchStore::class)->toResource();
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function delete(string $searchStoreId): void
    {
        $url = $this->url($this->apiVersion, $searchStoreId);

        $this->transport->deleteRequest($url, [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
            'query' => [
                'force' => 'true',
            ],
        ]);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function attachFile(
        string $searchStoreId,
        string $fileId,
        ?array $metadata = null,
    ): SearchStoreFile {
        // if (!$force && null !== $record = $this->findFile($searchStoreId, $fileId)) {
        //     return $this->mapSearchStoreFile($record, $searchStoreId, $fileId);
        // }

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
    public function readFile(
        string $searchStoreId,
        string $searchStoreFileId,
    ): SearchStoreFile {
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
    public function deleteFile(
        string $searchStoreId,
        string $searchStoreFileId,
    ): void {
        $url = $this->url($this->apiVersion, $searchStoreFileId);

        $this->transport->deleteRequest($url, [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
            'query' => [
                'force' => 'true',
            ],
        ]);
    }

    /*
    private function findFile(string $searchStoreId, string $fileId): ?Document
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
                $record = $this->createDocument($document);

                if (null !== $record && $fileId === ($this->metadata($record)[self::SOURCE_FILE_METADATA_KEY] ?? null)) {
                    return $record;
                }
            }

            $pageToken = $page->nextPageToken;
        } while (null !== $pageToken && '' !== $pageToken);

        return null;
    }
    */

    private function getFile(string $searchStoreFileId): Document
    {
        $response = $this->transport->getRequest($this->url($this->apiVersion, $searchStoreFileId), [
            'headers' => $this->headers(),
        ]);

        return $this->transport->decode($response, Document::class);
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

    /**
     * @param non-empty-string $searchStoreId
     * @param non-empty-string $fileId
     * @param ?array<string, string|int|float|bool> $metadata
     */
    private function mapSearchStoreFile(
        Document $record,
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
    private function metadata(Document $record): array
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
    private function createDocument(array $document): ?Document
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

        return new Document(
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
