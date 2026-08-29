<?php

namespace OneToMany\AI\Contract\Bridge;

use OneToMany\AI\Resource\Index\Index;
use OneToMany\AI\Resource\Index\IndexFile;
use OneToMany\AI\Resource\Shared\Metadata;

interface IndexProviderInterface extends ProviderInterface
{
    /**
     * @param non-empty-string $name
     */
    public function create(string $name, bool $multimodal = false): Index;

    /**
     * @param non-empty-string $indexId
     */
    public function read(string $indexId): Index;

    /**
     * @param non-empty-string $indexId
     */
    public function delete(string $indexId): void;

    /**
     * @param non-empty-string $indexId
     * @param non-empty-string $fileId
     */
    public function attachFile(string $indexId, string $fileId, Metadata $metadata): IndexFile;

    /**
     * @param non-empty-string $indexId
     * @param non-empty-string $indexFileId
     */
    public function readFile(string $indexId, string $indexFileId): IndexFile;

    /**
     * @param non-empty-string $indexId
     * @param non-empty-string $indexFileId
     */
    public function deleteFile(string $indexId, string $indexFileId): void;
}
