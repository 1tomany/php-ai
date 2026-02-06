<?php

namespace OneToMany\AI\Client\OpenAi\Type\Response\Output;

use OneToMany\AI\Client\OpenAi\Type\Response\Enum\Role;
use OneToMany\AI\Client\OpenAi\Type\Response\Enum\Status;
use OneToMany\AI\Client\OpenAi\Type\Response\Output\Content\OutputTextType;
use OneToMany\AI\Client\OpenAi\Type\Response\Output\Enum\Type;
use OneToMany\AI\Exception\InvalidArgumentException;

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
            throw new InvalidArgumentException(\sprintf('The content must be a non-empty-list when the type is "%s".', Type::Message->getValue()));
        }
    }
}
