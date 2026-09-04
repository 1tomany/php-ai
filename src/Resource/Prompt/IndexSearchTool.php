<?php

namespace OneToMany\AI\Resource\Prompt;

use OneToMany\AI\Resource\Prompt\Enum\ToolType;

final readonly class IndexSearchTool extends AbstractTool
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
