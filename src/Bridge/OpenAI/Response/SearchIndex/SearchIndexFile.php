<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\SearchIndex;

final readonly class SearchIndexFile
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $vector_store_id
     * @param non-empty-string $status
     * @param ?array<string, string|int|float|bool> $attributes
     */
    public function __construct(
        public string $id,
        public string $vector_store_id,
        public string $status,
        public ?array $attributes = null,
    ) {
    }
}
