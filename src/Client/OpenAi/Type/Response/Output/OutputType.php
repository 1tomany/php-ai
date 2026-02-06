<?php

namespace OneToMany\AI\Client\OpenAi\Type\Response\Output;

use OneToMany\AI\Client\OpenAi\Type\Response\Enum\Role;
use OneToMany\AI\Client\OpenAi\Type\Response\Enum\Status;
use OneToMany\AI\Client\OpenAi\Type\Response\Output\Content\OutputTextType;
use OneToMany\AI\Client\OpenAi\Type\Response\Output\Enum\Type;
use OneToMany\AI\Exception\InvalidArgumentException;

use function array_map;
use function implode;
use function sprintf;
use function trim;

final readonly class OutputType
{
    /**
     * @param non-empty-string $id
     * @param ?non-empty-list<OutputTextType> $content
     */
    public function __construct(
        public Type $type,
        public string $id,
        public Status $status,
        public Role $role,
        public ?array $content = null,
    ) {
        if ($type->isMessage() && empty($content)) {
            throw new InvalidArgumentException(sprintf('The content must be a non-empty-list when the type is "%s".', Type::Message->getValue()));
        }
    }

    /**
     * @return ?non-empty-string
     */
    public function getOutput(): ?string
    {
        if (null !== $this->content) {
            return trim(implode('', array_map(fn ($c) => (string) $c->text, $this->content))) ?: null;
        }

        return null;
    }
}
