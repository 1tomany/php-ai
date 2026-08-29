<?php

namespace OneToMany\AI\Bridge\OpenAI;

use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\VectorStore;
use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\VectorStoreFile;
use OneToMany\AI\Contract\Bridge\IndexProviderInterface;
use OneToMany\AI\Resource\Index\Index;
use OneToMany\AI\Resource\Index\IndexFile;
use OneToMany\AI\Resource\Shared\Metadata;

final readonly class IndexProvider extends AbstractProvider implements IndexProviderInterface
{
    /**
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function create(
        string $name,
        ?string $model,
    ): Index {
        $url = $this->url('vector_stores');

        $response = $this->transport->postRequest($url, [
            'auth_bearer' => $this->apiKey,
            'json' => [
                'name' => $name,
            ],
        ]);

        return $this->transport->decode($response, VectorStore::class)->toResource();
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function read(string $indexId): Index
    {
        $url = $this->url('vector_stores', $indexId);

        $response = $this->transport->getRequest($url, [
            'auth_bearer' => $this->apiKey,
        ]);

        return $this->transport->decode($response, VectorStore::class)->toResource();
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function delete(string $indexId): void
    {
        $url = $this->url('vector_stores', $indexId);

        $this->transport->deleteRequest($url, [
            'auth_bearer' => $this->apiKey,
        ]);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function attachFile(
        string $indexId,
        string $fileId,
        Metadata $metadata,
    ): IndexFile {
        $url = $this->url('vector_stores', $indexId, 'files');

        $response = $this->transport->postRequest($url, [
            'auth_bearer' => $this->apiKey,
            'json' => [
                'file_id' => $fileId,
            ],
        ]);

        return $this->transport->decode($response, VectorStoreFile::class)->toResource();
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function readFile(
        string $indexId,
        string $indexFileId,
    ): IndexFile {
        $url = $this->url('vector_stores', $indexId, 'files', $indexFileId);

        $response = $this->transport->getRequest($url, [
            'auth_bearer' => $this->apiKey,
        ]);

        return $this->transport->decode($response, VectorStoreFile::class)->toResource();
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function deleteFile(
        string $indexId,
        string $indexFileId,
    ): void {
        $url = $this->url('vector_stores', $indexId, 'files', $indexFileId);

        $this->transport->deleteRequest($url, [
            'auth_bearer' => $this->apiKey,
        ]);
    }
}
