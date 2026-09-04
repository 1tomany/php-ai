<?php

namespace OneToMany\AI\Resource\Prompt\ToolResult;

use OneToMany\AI\Resource\Prompt\Enum\ToolType;
use OneToMany\AI\Resource\Prompt\ToolResult;

final readonly class IndexSearchResult extends ToolResult
{
    /**
     * @param non-empty-string $id
     * @param list<non-empty-string> $queries
     * @param ?list<IndexSearchMatch> $results
     */
    public function __construct(
        string $id,
        public array $queries = [],
        public ?array $results = null,
        bool $completed = true,
    ) {
        parent::__construct($id, ToolType::IndexSearch, $completed);
    }

    /**
     * @return list<non-empty-string>
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     * Null means results were not provided; an empty list means no matches were found.
     *
     * @return ?list<IndexSearchMatch>
     */
    public function getResults(): ?array
    {
        return $this->results;
    }
}
