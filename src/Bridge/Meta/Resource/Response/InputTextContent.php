<?php

namespace OneToMany\AI\Bridge\Meta\Resource\Response;

use OneToMany\AI\Exception\DomainException;

use function trim;

/**
 * @extends InputContent<'input_text'>
 */
final readonly class InputTextContent extends InputContent
{
    /**
     * @var non-empty-string
     */
    public string $text;

    /**
     * @see OneToMany\AI\Bridge\Meta\Resource\Response\InputContent
     *
     * @throws DomainException when the text is empty
     */
    public function __construct(?string $text)
    {
        parent::__construct('input_text');

        if ('' === $text = trim((string) $text)) {
            throw new DomainException('The text cannot be empty.');
        }

        $this->text = $text;
    }

    /**
     * @see \JsonSerializable
     *
     * @return array{
     *   type: 'input_text',
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
