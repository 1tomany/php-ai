<?php

namespace OneToMany\AI\Resource\Query;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Exception\RuntimeException;

use function file_get_contents;
use function sprintf;
use function trim;

final readonly class InputText implements \Stringable
{
    /**
     * @var non-empty-string
     */
    public string $text;

    /**
     * @throws DomainException when the input text is empty
     */
    public function __construct(?string $text)
    {
        if ('' === $text = trim((string) $text)) {
            throw new DomainException('The input text cannot be empty.');
        }

        $this->text = $text;
    }

    /**
     * @see \Stringable
     *
     * @return non-empty-string
     */
    #[\Override]
    public function __toString(): string
    {
        return $this->getText();
    }

    /**
     * @throws RuntimeException when reading the file fails
     */
    public static function fromFile(string $path): static
    {
        if (false === $text = @file_get_contents($path)) {
            throw new RuntimeException(sprintf('Reading the input text file "%s" failed.', $path));
        }

        return new static($text);
    }

    /**
     * @return non-empty-string
     */
    public function getText(): string
    {
        return $this->text;
    }
}
