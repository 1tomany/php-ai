<?php

namespace OneToMany\AI\Contract\Action\File;

interface UploadFileActionInterface
{
    public function act(UploadRequest $request): UploadResponse;
}
