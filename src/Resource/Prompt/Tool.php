<?php

namespace OneToMany\AI\Resource\Prompt;

use OneToMany\AI\Resource\Prompt\Enum\ToolType;
use OneToMany\AI\Resource\Prompt\Tool\IndexSearch;

readonly class Tool
{
    public function __construct(
        public ToolType $type,
    ) {
    }

    /**
     * @param list<non-empty-string> $indexIds
     */
    public static function indexSearch(array $indexIds): IndexSearch
    {
        return new IndexSearch($indexIds);
    }

    public function getType(): ToolType
    {
        return $this->type;
    }
}
