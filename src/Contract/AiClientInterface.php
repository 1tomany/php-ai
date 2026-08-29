<?php

namespace OneToMany\AI\Contract;

use OneToMany\AI\Contract\Resource\FilesInterface;
use OneToMany\AI\Contract\Resource\QueriesInterface;
use OneToMany\AI\Contract\Resource\IndexesInterface;

interface AiClientInterface
{
    public FilesInterface $files { get; }

    public QueriesInterface $queries { get; }

    public IndexesInterface $searchStores { get; }
}
