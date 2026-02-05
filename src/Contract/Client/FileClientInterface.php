<?php

namespace OneToMany\AI\Contract\Client;

interface FileClientInterface extends ModelClientInterface
{
    public function upload(UploadRequest $request): UploadResponse;
}
