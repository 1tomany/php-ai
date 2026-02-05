<?php

namespace App\File\Vendor\AI\Client\Mock;

use App\File\Vendor\AI\Contract\Client\FileClientInterface;
use App\File\Vendor\AI\Request\File\UploadRequest;
use App\File\Vendor\AI\Response\File\UploadResponse;

use function in_array;

final readonly class FileClient extends BaseClient implements FileClientInterface
{
    /**
     * @see App\File\Vendor\AI\Contract\Client\FileClientInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        return new UploadResponse($this->generateUri('file'));
    }

    /**
     * @see App\File\Vendor\AI\Contract\Client\ModelClientInterface
     */
    public function supportsRequest(object $request): bool
    {
        return $request instanceof UploadRequest && in_array($request->getModel(), $this->getSupportedModels());
    }
}
