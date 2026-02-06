<?php

namespace OneToMany\AI\Client\OpenAi\Type\Response\Output\Content;

use OneToMany\AI\Client\OpenAi\Type\Response\Output\Content\Enum\Type;
use OneToMany\AI\Exception\InvalidArgumentException;

use function sprintf;

final readonly class OutputText
{
    /**
     * @param ?non-empty-string $text
     * @param ?non-empty-string $refusal
     */
    public function __construct(
        public Type $type,
        public ?string $text = null,
        public ?string $refusal = null,
    ) {
        if (!$text && !$refusal) {
            throw new InvalidArgumentException('Both the text and refusal cannot be empty.');
        }

        if ($type->isOutputText() && !$text) {
            throw new InvalidArgumentException(sprintf('The text cannot be empty when the type is "%s".', Type::OutputText->getValue()));
        }

        if ($type->isRefusal() && !$refusal) {
            throw new InvalidArgumentException(sprintf('The refusal cannot be empty when the type is "%s".', Type::Refusal->getValue()));
        }
    }
}
