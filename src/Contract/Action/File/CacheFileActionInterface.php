<?php

namespace OneToMany\AI\Contract\Action\File;

use OneToMany\AI\Contract\Input\File\CacheFileInputInterface;
use OneToMany\AI\Contract\Response\File\CachedFileResponseInterface;

interface CacheFileActionInterface
{
    public function act(CacheFileInputInterface $request): CachedFileResponseInterface;
}
