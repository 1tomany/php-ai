<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore\Enum;

enum ChunkingStrategyType: string
{
    case Static = 'static';
    case Other = 'other';
}
