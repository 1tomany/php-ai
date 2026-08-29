<?php

namespace OneToMany\AI\Contract;

use OneToMany\AI\Contract\Resource\FilesInterface;
use OneToMany\AI\Contract\Resource\IndexesInterface;
use OneToMany\AI\Contract\Resource\PromptsInterface;

interface AiClientInterface
{
    public FilesInterface $files { get; }

    public PromptsInterface $prompts { get; }

    public IndexesInterface $indexes { get; }
}
