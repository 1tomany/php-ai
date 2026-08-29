<?php

namespace OneToMany\AI\Bridge\Gemini;

use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Document;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\FileSearchStore;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Operation;
use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Resource\Index\SearchStore;
use OneToMany\AI\Resource\Index\SearchStoreFile;
use OneToMany\AI\Resource\Shared\Metadata;

use function sprintf;

final readonly class SearchStoreProvider extends AbstractProvider implements SearchStoreProviderInterface
{
    public const string MULTIMODAL_EMBEDDING_MODEL = 'models/gemini-embedding-2';

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function create(
        string $name,
        ?string $model,
    ): SearchStore {
        $url = $this->url($this->apiVersion, 'fileSearchStores');

        if ('' === $model = trim((string) $model)) {
            $model = self::MULTIMODAL_EMBEDDING_MODEL;
        }

        $response = $this->transport->postRequest($url, [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
            'json' => [
                'displayName' => $name,
                'embeddingModel' => $model,
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
        Metadata $metadata,
    ): SearchStoreFile {
        $url = $this->url($this->apiVersion, sprintf('%s:importFile', $searchStoreId));

        $response = $this->transport->postRequest($url, [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
            'json' => [
                'fileName' => $fileId,
            ],
        ]);

        $operation = $this->transport->decode($response, Operation::class);

        return $this->readFile($searchStoreId, $operation->id);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function readFile(
        string $searchStoreId,
        string $searchStoreFileId,
    ): SearchStoreFile {
        $url = $this->url($this->apiVersion, $searchStoreFileId);

        $response = $this->transport->getRequest($url, [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
        ]);

        return $this->transport->decode($response, Document::class)->toResource();
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
}
