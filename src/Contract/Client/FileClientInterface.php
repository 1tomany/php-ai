<?php

namespace OneToMany\AI\Contract\Client;

use OneToMany\AI\Request\File\UploadRequest;
use OneToMany\AI\Response\File\UploadResponse;

interface FileClientInterface extends ClientInterface
{
    public function upload(UploadRequest $request): UploadResponse;
}
