<?php

namespace OneToMany\AI\Resource\Prompt;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Exception\RuntimeException;

use function is_array;
use function json_decode;

use const JSON_THROW_ON_ERROR;

final readonly class Response
{
    /**
     * @param non-empty-string $id
     * @param ?non-empty-string $text
     * @param ?non-empty-string $refusal
     * @param ?non-empty-string $error
     * @param list<non-empty-string> $fileIds
     */
    public function __construct(
        public string $id,
        public bool $completed = true,
        public ?string $text = null,
        public ?string $refusal = null,
        public ?string $error = null,
        public Usage $usage = new Usage(),
        public array $fileIds = [],
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @return ?non-empty-string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @return ?non-empty-string
     */
    public function getRefusal(): ?string
    {
        return $this->refusal;
    }

    /**
     * @return ?non-empty-string
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    public function getUsage(): Usage
    {
        return $this->usage;
    }

    /**
     * @return list<non-empty-string>
     */
    public function getFileIds(): array
    {
        return $this->fileIds;
    }

    /**
     * @return array<array-key, mixed>
     *
     * @throws DomainException when the response text is empty
     * @throws RuntimeException when decoding the response text fails
     * @throws RuntimeException when the decoded response is not an array
     */
    public function toArray(): array
    {
        if (null === $text = $this->getText()) {
            throw new DomainException('The response text is empty.');
        }

        try {
            $array = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new RuntimeException('Decoding the response text failed.', previous: $e);
        }

        if (!is_array($array)) {
            throw new RuntimeException('The decoded response text was expected to be an array.');
        }

        return $array;
    }
}
