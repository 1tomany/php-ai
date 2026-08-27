<?php

namespace OneToMany\AI\Bridge\OpenAI;

use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\VectorStore as VectorStoreRecord;
use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\VectorStoreFile as VectorStoreFileRecord;
use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;
use OneToMany\AI\Resource\SearchStore\Statistics;

final readonly class SearchStoreProvider extends AbstractProvider implements SearchStoreProviderInterface
{
    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function create(string $name, ?string $description = null): SearchStore
    {
        $payload = ['name' => $name];

        if (null !== $description) {
            $payload['description'] = $description;
        }

        $url = $this->url('vector_stores');

        try {
            $response = $this->transport->postRequest($url, [
                'auth_bearer' => $this->apiKey,
                'headers' => $this->headers(),
                'json' => [
                    ...$payload,
                ],
            ]);
        } finally {
            unset($payload);
        }

        return $this->mapSearchStore($this->transport->decode($response, VectorStoreRecord::class));
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function read(string $searchStoreId): SearchStore
    {
        $url = $this->url('vector_stores', $searchStoreId);

        $response = $this->transport->getRequest($url, [
            'auth_bearer' => $this->apiKey,
            'headers' => $this->headers(),
        ]);

        return $this->mapSearchStore($this->transport->decode($response, VectorStoreRecord::class));
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function delete(string $searchStoreId): void
    {
        $url = $this->url('vector_stores', $searchStoreId);

        $this->transport->deleteRequest($url, [
            'auth_bearer' => $this->apiKey,
            'headers' => $this->headers(),
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
            return $this->mapSearchStoreFile($record, $fileId);
        }

        $payload = ['file_id' => $fileId];

        if ([] !== $metadata) {
            $payload['attributes'] = $metadata;
        }

        $url = $this->url('vector_stores', $searchStoreId, 'files');

        try {
            $response = $this->transport->postRequest($url, [
                'auth_bearer' => $this->apiKey,
                'headers' => $this->headers(),
                'json' => [
                    ...$payload,
                ],
            ]);
        } finally {
            unset($payload);
        }

        $record = $this->transport->decode($response, VectorStoreFileRecord::class);

        return $this->mapSearchStoreFile($record, $fileId);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function readFile(string $searchStoreId, string $searchStoreFileId): SearchStoreFile
    {
        $record = $this->getFile($searchStoreId, $searchStoreFileId);

        return $this->mapSearchStoreFile($record, $record->id);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function deleteFile(string $searchStoreId, string $searchStoreFileId): void
    {
        $url = $this->url('vector_stores', $searchStoreId, 'files', $searchStoreFileId);

        $this->transport->deleteRequest($url, [
            'auth_bearer' => $this->apiKey,
            'headers' => $this->headers(),
        ]);
    }

    private function findFile(string $searchStoreId, string $fileId): ?VectorStoreFileRecord
    {
        try {
            return $this->getFile($searchStoreId, $fileId);
        } catch (RuntimeException $e) {
            if (404 === $e->getCode()) {
                return null;
            }

            throw $e;
        }
    }

    private function getFile(string $searchStoreId, string $searchStoreFileId): VectorStoreFileRecord
    {
        $url = $this->url('vector_stores', $searchStoreId, 'files', $searchStoreFileId);

        $response = $this->transport->getRequest($url, [
            'auth_bearer' => $this->apiKey,
            'headers' => $this->headers(),
        ]);

        return $this->transport->decode($response, VectorStoreFileRecord::class);
    }

    private function mapSearchStore(VectorStoreRecord $record): SearchStore
    {
        return new SearchStore(
            $record->id,
            $record->name,
            $record->description,
            $record->status,
            new Statistics(
                $record->file_counts->total,
                $record->file_counts->completed,
                $record->file_counts->in_progress,
                $record->file_counts->failed,
                $record->file_counts->cancelled,
            ),
        );
    }

    /**
     * @param non-empty-string $fileId
     */
    private function mapSearchStoreFile(
        VectorStoreFileRecord $record,
        string $fileId,
    ): SearchStoreFile {
        return new SearchStoreFile($record->id, $record->vector_store_id, $fileId, $record->status, $record->attributes ?? []);
    }

    /**
     * @return array{OpenAI-Beta: 'assistants=v2'}
     */
    private function headers(): array
    {
        return ['OpenAI-Beta' => 'assistants=v2'];
    }
}
