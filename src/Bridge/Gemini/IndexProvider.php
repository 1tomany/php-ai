<?php

namespace OneToMany\AI\Bridge\Gemini;

use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Document;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\FileSearchStore;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Operation;
use OneToMany\AI\Contract\Bridge\IndexProviderInterface;
use OneToMany\AI\Resource\Index\Index;
use OneToMany\AI\Resource\Index\SearchStoreFile;
use OneToMany\AI\Resource\Shared\Metadata;

use function sprintf;

final readonly class IndexProvider extends AbstractProvider implements IndexProviderInterface
{
    public const string MULTIMODAL_EMBEDDING_MODEL = 'models/gemini-embedding-2';

    /**
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function create(
        string $name,
        ?string $model,
    ): Index {
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
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function read(string $indexId): Index
    {
        $url = $this->url($this->apiVersion, $indexId);

        $response = $this->transport->getRequest($url, [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
        ]);

        return $this->transport->decode($response, FileSearchStore::class)->toResource();
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function delete(string $indexId): void
    {
        $url = $this->url($this->apiVersion, $indexId);

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
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function attachFile(
        string $indexId,
        string $fileId,
        Metadata $metadata,
    ): SearchStoreFile {
        $url = $this->url($this->apiVersion, sprintf('%s:importFile', $indexId));

        $response = $this->transport->postRequest($url, [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
            'json' => [
                'fileName' => $fileId,
            ],
        ]);

        $operation = $this->transport->decode($response, Operation::class);

        return $this->readFile($indexId, $operation->id);
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function readFile(
        string $indexId,
        string $indexFileId,
    ): SearchStoreFile {
        $url = $this->url($this->apiVersion, $indexFileId);

        $response = $this->transport->getRequest($url, [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
        ]);

        return $this->transport->decode($response, Document::class)->toResource();
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function deleteFile(
        string $indexId,
        string $indexFileId,
    ): void {
        $url = $this->url($this->apiVersion, $indexFileId);

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
