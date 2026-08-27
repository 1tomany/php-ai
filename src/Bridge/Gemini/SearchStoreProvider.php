<?php

namespace OneToMany\AI\Bridge\Gemini;

use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Document;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\FileSearchStore;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\ImportFileResponse;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Operation;
use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Exception\RuntimeException;
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
        Metadata $metadata,
    ): SearchStoreFile {
        throw new RuntimeException('Not implemented!');
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
