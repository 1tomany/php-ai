<?php

namespace OneToMany\AI\Contract\Client;

use OneToMany\AI\Contract\Input\File\CacheFileInputInterface;
use OneToMany\AI\Contract\Response\File\CachedFileResponseInterface;

interface FileClientInterface
{
    public function cache(CacheFileInputInterface $request): CachedFileResponseInterface;
}
