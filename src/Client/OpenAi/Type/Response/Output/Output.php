<?php

namespace OneToMany\AI\Client\OpenAi\Type\Response\Output;

use OneToMany\AI\Client\OpenAi\Type\Response\Enum\Role;
use OneToMany\AI\Client\OpenAi\Type\Response\Enum\Status;
use OneToMany\AI\Client\OpenAi\Type\Response\Output\Content\OutputText;
use OneToMany\AI\Client\OpenAi\Type\Response\Output\Enum\Type;
use OneToMany\AI\Exception\InvalidArgumentException;

use function array_map;
use function implode;
use function sprintf;
use function trim;

final readonly class Output
{
    /**
     * @param non-empty-string $id
     * @param ?list<OutputText> $content
     */
    public function __construct(
        public string $id,
        public Type $type,
        public ?Status $status = null,
        public ?array $content = null,
        public ?Role $role = null,
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
        if (!$this->content) {
            return null;
        }

        if ($this->type->isMessage() && $this->status?->isCompleted()) {
            $output = array_map(fn ($c) => (string) $c->text, $this->content);
        }

        return trim(implode('', $output ?? [])) ?: null;
    }
}
