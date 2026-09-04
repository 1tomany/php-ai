<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

use Symfony\Component\Serializer\Attribute\DiscriminatorMap;

#[DiscriminatorMap(
    typeProperty: 'type',
    mapping: [
        'output_text' => ResponseOutputText::class,
        'refusal' => ResponseOutputRefusal::class,
    ],
)]
interface ContentInterface
{
    /**
     * @var non-empty-string
     */
    public string $type { get; }
}
