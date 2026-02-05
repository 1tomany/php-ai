<?php

namespace OneToMany\AI\Response\File;

use OneToMany\AI\Response\BaseResponse;

use function strtolower;

final readonly class UploadResponse extends BaseResponse
{
    /**
     * @param non-empty-lowercase-string $model
     * @param non-empty-string $uri
     * @param ?non-empty-string $name
     * @param ?non-empty-string $purpose
     */
    public function __construct(
        string $model,
        private string $uri,
        private ?string $name = null,
        private ?string $purpose = null,
        private ?\DateTimeImmutable $expiresAt = null,
    ) {
        parent::__construct($model);
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return ?non-empty-string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return ?non-empty-lowercase-string
     */
    public function getPurpose(): ?string
    {
        return $this->purpose ? strtolower($this->purpose) : null;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }
}
