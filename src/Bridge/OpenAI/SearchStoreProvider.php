<?php

namespace OneToMany\AI\Bridge\OpenAI;

use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\VectorStore;
use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\VectorStoreFile;
use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;
use OneToMany\AI\Resource\Shared\Metadata;

final readonly class SearchStoreProvider extends AbstractProvider implements SearchStoreProviderInterface
{
    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function create(
        string $name,
        ?string $description = null,
    ): SearchStore {
        $url = $this->url('vector_stores');

        $response = $this->transport->postRequest($url, [
            'auth_bearer' => $this->apiKey,
            'json' => [
                'name' => $name,
                'description' => $description,
            ],
        ]);

        return $this->transport->decode($response, VectorStore::class)->toResource();
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
        ]);

        return $this->transport->decode($response, VectorStore::class)->toResource();
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
        $url = $this->url('vector_stores', $searchStoreId, 'files');

        $response = $this->transport->postRequest($url, [
            'auth_bearer' => $this->apiKey,
            'json' => [
                'file_id' => $fileId,
            ],
        ]);

        return $this->transport->decode($response, VectorStoreFile::class)->toResource();
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function readFile(
        string $searchStoreId,
        string $searchStoreFileId,
    ): SearchStoreFile {
        $url = $this->url('vector_stores', $searchStoreId, 'files', $searchStoreFileId);

        $response = $this->transport->getRequest($url, [
            'auth_bearer' => $this->apiKey,
        ]);

        return $this->transport->decode($response, VectorStoreFile::class)->toResource();
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface
     */
    #[\Override]
    public function deleteFile(
        string $searchStoreId,
        string $searchStoreFileId,
    ): void {
        $url = $this->url('vector_stores', $searchStoreId, 'files', $searchStoreFileId);

        $this->transport->deleteRequest($url, [
            'auth_bearer' => $this->apiKey,
        ]);
    }
}
