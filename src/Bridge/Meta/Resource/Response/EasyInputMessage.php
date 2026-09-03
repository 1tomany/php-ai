<?php

namespace OneToMany\AI\Bridge\Meta\Resource\Response;

use function strtolower;

final class EasyInputMessage implements \JsonSerializable
{
    /**
     * @var 'assistant'|'developer'|'system'|'user'
     */
    private string $role = 'user';

    /**
     * @var list<InputFileContent|InputImageContent|InputTextContent>
     */
    private array $content = [];

    /**
     * @param 'assistant'|'developer'|'system'|'user' $role
     */
    public function __construct(
        string $role = 'user',
    ) {
        $this->role = strtolower($role);
    }

    public function addContent(InputFileContent|InputImageContent|InputTextContent $input): static
    {
        $this->content[] = $input;

        return $this;
    }

    /**
     * @see \JsonSerializable
     *
     * @return array{
     *   role: 'assistant'|'developer'|'system'|'user',
     *   content: list<InputFileContent|InputImageContent|InputTextContent>,
     * }
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'role' => $this->role,
            'content' => $this->content,
        ];
    }
}
