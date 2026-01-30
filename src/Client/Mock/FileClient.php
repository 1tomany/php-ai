<?php

namespace OneToMany\AI\Client\Mock;

use OneToMany\AI\Client\Mock\Trait\GenerateUriTrait;
use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Contract\Input\File\CacheFileInputInterface;
use OneToMany\AI\Contract\Response\File\CachedFileResponseInterface;
use OneToMany\AI\Response\File\CachedFileResponse;

final readonly class FileClient implements FileClientInterface
{
    use GenerateUriTrait;

    public function __construct()
    {
    }

    public function cache(CacheFileInputInterface $request): CachedFileResponseInterface
    {
        return new CachedFileResponse($request->getVendor(), $this->generateUri('file'), null, $request->getFormat());
    }
}
