<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

use Symfony\Component\Serializer\Attribute\DiscriminatorMap;

#[DiscriminatorMap(
    typeProperty: 'type',
    mapping: [
        'file_citation' => FileCitation::class,
        'file_path' => FilePath::class,
        'url_citation' => URLCitation::class,
    ],
)]
interface AnnotationInterface
{
    /**
     * @var non-empty-string
     */
    public string $type { get; }
}
