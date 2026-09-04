<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class ResponseOutputText implements ContentInterface
{
    /**
     * @param 'output_text' $type
     * @param list<AnnotationInterface> $annotations
     */
    public function __construct(
        public string $type,
        public string $text,
        public array $annotations = [],
    ) {
    }
}
