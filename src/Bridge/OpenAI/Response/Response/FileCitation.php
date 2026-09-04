<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class FileCitation
{
    /**
     * @param 'file_citation' $type
     * @param non-empty-string $file_id
     * @param non-empty-string $filename
     * @param non-negative-int $index
     */
    public function __construct(
        public string $type,
        public string $file_id,
        public string $filename,
        public int $index = 0,
    ) {
    }
}
