<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class FilePath
{
    /**
     * @param 'file_path' $type
     * @param non-empty-string $file_id
     * @param non-negative-int $index
     */
    public function __construct(
        public string $type,
        public string $file_id,
        public int $index = 0,
    ) {
    }
}
