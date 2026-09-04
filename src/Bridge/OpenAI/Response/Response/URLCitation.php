<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class URLCitation implements AnnotationInterface
{
    /**
     * @param 'url_citation' $type
     * @param ?non-empty-string $title
     * @param non-empty-string $url
     * @param non-negative-int $start_index
     * @param non-negative-int $end_index
     */
    public function __construct(
        public string $type,
        public ?string $title,
        public string $url,
        public int $start_index = 0,
        public int $end_index = 0,
    ) {
    }
}
