<?php

namespace OneToMany\AI\Contract;

use OneToMany\AI\Contract\Resource\FilesInterface;
use OneToMany\AI\Contract\Resource\IndexesInterface;
use OneToMany\AI\Contract\Resource\QueriesInterface;

interface AiClientInterface
{
    public FilesInterface $files { get; }

    public QueriesInterface $prompts { get; }

    public IndexesInterface $indexes { get; }
}
