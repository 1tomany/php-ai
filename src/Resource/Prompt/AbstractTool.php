<?php

namespace OneToMany\AI\Resource\Prompt;

use OneToMany\AI\Resource\Prompt\Enum\ToolType;

abstract readonly class AbstractTool
{
    public function __construct(
        public ToolType $type,
    ) {
    }

    public function getType(): ToolType
    {
        return $this->type;
    }
}
