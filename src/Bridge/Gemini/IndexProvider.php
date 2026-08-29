<?php

namespace OneToMany\AI\Bridge\Gemini;

use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Document;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\FileSearchStore;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\ImportFileOperation;
use OneToMany\AI\Contract\Bridge\IndexProviderInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Resource\Index\Index;
use OneToMany\AI\Resource\Index\IndexFile;
use OneToMany\AI\Resource\Shared\Metadata;

use function sleep;
use function sprintf;

final readonly class IndexProvider extends AbstractProvider implements IndexProviderInterface
{
    /**
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function create(
        string $name,
        bool $multimodal = false,
    ): Index {
        $url = $this->url($this->apiVersion, 'fileSearchStores');

        // Determine if we're using a multimodal model or text only
        $model = $multimodal ? 'models/gemini-embedding-2' : null;

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
    ): IndexFile {
        $url = $this->url($this->apiVersion, sprintf('%s:importFile', $indexId));

        $response = $this->transport->postRequest($url, [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
            'json' => [
                'fileName' => $fileId,
            ],
        ]);

        $operation = $this->transport->decode($response, ImportFileOperation::class);

        return $this->readFile($indexId, $this->waitForOperation($operation));
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\IndexProviderInterface
     */
    #[\Override]
    public function readFile(
        string $indexId,
        string $indexFileId,
    ): IndexFile {
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

    /**
     * @return non-empty-string
     *
     * @throws RuntimeException when waiting for the operation times out
     * @throws RuntimeException when the operation fails
     * @throws RuntimeException when the operation does not return a document name
     */
    private function waitForOperation(ImportFileOperation $operation): string
    {
        $pollCount = 0;

        do {
            if ($pollCount >= $operation::POLL_MAX_COUNT) {
                throw new RuntimeException(sprintf('Waiting for the operation "%s" to complete timed out.', $operation->name));
            }

            $url = $this->url($this->apiVersion, $operation->name);

            $response = $this->transport->getRequest($url, [
                'headers' => [
                    'x-goog-api-key' => $this->apiKey,
                ],
            ]);

            $operation = $this->transport->decode($response, ImportFileOperation::class);

            if ($operation->done) {
                break;
            }

            sleep($operation::POLL_SLEEP_SECONDS);

            ++$pollCount;
        } while (true);

        if (null !== $operation->error) {
            throw new RuntimeException(sprintf('The operation "%s" failed: %s.', $operation->name, \rtrim($operation->error->message, '.')), $operation->error->code);
        }

        if (null === $operation->response) {
            throw new RuntimeException(sprintf('The operation "%s" did not return a response.', $operation->name));
        }

        return $operation->response->documentName;
    }
}
