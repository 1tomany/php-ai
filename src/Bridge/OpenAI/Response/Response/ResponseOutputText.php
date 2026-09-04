<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class ResponseOutputText
{
    /**
     * @param 'output_text' $type
     * @param list<FileCitation|FilePath|URLCitation> $annotations
     */
    public function __construct(
        public string $type,
        public string $text,
        public array $annotations = [],
    ) {
    }
}
