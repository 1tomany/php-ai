<?php

namespace OneToMany\AI\Action\File;

final readonly class UploadFileAction implements UploadFileActionInterface
{
    public function __construct(private FileClientFactory $clientFactory)
    {
    }

    /**
     * @see App\File\Vendor\AI\Contract\Action\File\UploadFileActionInterface
     */
    public function act(UploadRequest $request): UploadResponse
    {
        return $this->clientFactory->create($request)->upload($request);
    }
}
