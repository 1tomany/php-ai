<?php

namespace OneToMany\AI\Bridge\Gemini\Resource\Interaction;

use OneToMany\AI\Exception\DomainException;

use function trim;

/**
 * @extends Content<'text'>
 */
final readonly class TextContent extends Content implements \JsonSerializable
{
    /**
     * @var non-empty-string
     */
    public string $text;

    /**
     * @see OneToMany\AI\Bridge\Gemini\Resource\Interaction\Content
     *
     * @throws DomainException when the text is empty
     */
    public function __construct(
        ?string $text,
    ) {
        parent::__construct('text');

        if ('' === $text = trim((string) $text)) {
            throw new DomainException('The text cannot be empty.');
        }

        $this->text = $text;
    }

    /**
     * @see \JsonSerializable
     *
     * @return array{
     *   type: 'text',
     *   text: non-empty-string,
     * }
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'text' => $this->text,
        ];
    }
}
