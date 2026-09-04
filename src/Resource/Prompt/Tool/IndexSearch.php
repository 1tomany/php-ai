<?php

namespace OneToMany\AI\Resource\Prompt\Tool;

use OneToMany\AI\Resource\Prompt\Enum\ToolType;
use OneToMany\AI\Resource\Prompt\Tool;

final readonly class IndexSearch extends Tool
{
    /**
     * @param list<non-empty-string> $indexIds
     */
    public function __construct(
        public array $indexIds,
    ) {
        parent::__construct(ToolType::IndexSearch);
    }

    /**
     * @return list<non-empty-string>
     */
    public function getIndexIds(): array
    {
        return $this->indexIds;
    }
}
