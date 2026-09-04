<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

use Symfony\Component\Serializer\Attribute\DiscriminatorMap;

#[DiscriminatorMap(
    typeProperty: 'type',
    mapping: [
        'compaction' => Compaction::class,
        'file_search_call' => FileSearchCall::class,
        'function_call' => FunctionCall::class,
        'message' => ResponseOutputMessage::class,
        'reasoning' => Reasoning::class,
        'web_search_call' => WebSearchCall::class,
    ],
)]
interface OutputInterface
{
    /**
     * @var non-empty-string
     */
    public string $type { get; }
}
