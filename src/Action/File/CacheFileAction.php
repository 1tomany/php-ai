<?php

namespace OneToMany\AI\Action\File;

use OneToMany\AI\Contract\Action\File\CacheFileActionInterface;
use OneToMany\AI\Contract\Input\File\CacheFileInputInterface;
use OneToMany\AI\Contract\Response\File\CachedFileResponseInterface;
use OneToMany\AI\Factory\FileClientFactory;

final readonly class CacheFileAction implements CacheFileActionInterface
{
    public function __construct(private FileClientFactory $fileClientFactory)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Action\File\CacheFileActionInterface
     */
    public function act(CacheFileInputInterface $request): CachedFileResponseInterface
    {
        return $this->fileClientFactory->create($request->getVendor())->cache($request);
    }
}
