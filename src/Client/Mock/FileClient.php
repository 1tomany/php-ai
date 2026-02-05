<?php

namespace OneToMany\AI\Client\Mock;

use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Request\File\UploadRequest;
use OneToMany\AI\Response\File\UploadResponse;

use function in_array;

final readonly class FileClient extends BaseClient implements FileClientInterface
{
    /**
     * @see OneToMany\AI\Contract\Client\FileClientInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        return new UploadResponse($request->getModel(), $this->generateUri('file'));
    }

    /**
     * @see OneToMany\AI\Contract\Client\ClientInterface
     */
    public function supportsRequest(object $request): bool
    {
        return $request instanceof UploadRequest && in_array($request->getModel(), $this->getSupportedModels());
    }
}
