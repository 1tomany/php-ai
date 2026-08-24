<?php

namespace OneToMany\AI\Bridge\OpenAI;

use OneToMany\AI\Bridge\OpenAI\Response\SearchIndex\SearchIndex as SearchIndexRecord;
use OneToMany\AI\Bridge\OpenAI\Response\SearchIndex\SearchIndexFile as SearchIndexFileRecord;
use OneToMany\AI\Contract\Bridge\SearchIndexProviderInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Resource\SearchIndex\SearchIndex;
use OneToMany\AI\Resource\SearchIndex\SearchIndexFile;
use OneToMany\AI\Resource\SearchIndex\Statistics;

final readonly class SearchIndexProvider extends AbstractProvider implements SearchIndexProviderInterface
{
    /**
     * @see OneToMany\AI\Contract\Bridge\SearchIndexProviderInterface
     */
    #[\Override]
    public function create(string $name, ?string $description = null): SearchIndex
    {
        $payload = ['name' => $name];

        if (null !== $description) {
            $payload['description'] = $description;
        }

        $response = $this->transport->postRequest($this->url('vector_stores'), [
            'auth_bearer' => $this->apiKey,
            'headers' => $this->headers(),
            'json' => $payload,
        ]);

        return $this->mapSearchIndex($this->transport->decode($response, SearchIndexRecord::class));
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
            return $this->mapSearchIndexFile($record, $fileId);
        }

        $payload = ['file_id' => $fileId];

        if ([] !== $metadata) {
            $payload['attributes'] = $metadata;
        }

        $response = $this->transport->postRequest($this->url('vector_stores', $searchIndexId, 'files'), [
            'auth_bearer' => $this->apiKey,
            'headers' => $this->headers(),
            'json' => $payload,
        ]);

        $record = $this->transport->decode($response, SearchIndexFileRecord::class);

        return $this->mapSearchIndexFile($record, $fileId);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchIndexProviderInterface
     */
    #[\Override]
    public function removeFile(string $searchIndexId, string $searchIndexFileId): void
    {
        $this->transport->deleteRequest($this->url('vector_stores', $searchIndexId, 'files', $searchIndexFileId), [
            'auth_bearer' => $this->apiKey,
            'headers' => $this->headers(),
        ]);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchIndexProviderInterface
     */
    #[\Override]
    public function read(string $searchIndexId): SearchIndex
    {
        $response = $this->transport->getRequest($this->url('vector_stores', $searchIndexId), [
            'auth_bearer' => $this->apiKey,
            'headers' => $this->headers(),
        ]);

        return $this->mapSearchIndex($this->transport->decode($response, SearchIndexRecord::class));
    }

    private function findFile(string $searchIndexId, string $fileId): ?SearchIndexFileRecord
    {
        try {
            $response = $this->transport->getRequest($this->url('vector_stores', $searchIndexId, 'files', $fileId), [
                'auth_bearer' => $this->apiKey,
                'headers' => $this->headers(),
            ]);
        } catch (RuntimeException $e) {
            if (404 === $e->getCode()) {
                return null;
            }

            throw $e;
        }

        return $this->transport->decode($response, SearchIndexFileRecord::class);
    }

    private function mapSearchIndex(SearchIndexRecord $record): SearchIndex
    {
        return new SearchIndex(
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
    private function mapSearchIndexFile(SearchIndexFileRecord $record, string $fileId): SearchIndexFile
    {
        return new SearchIndexFile(
            $record->id,
            $record->vector_store_id,
            $fileId,
            $record->status,
            $record->attributes ?? [],
        );
    }

    /**
     * @return array{OpenAI-Beta: 'assistants=v2'}
     */
    private function headers(): array
    {
        return ['OpenAI-Beta' => 'assistants=v2'];
    }
}
