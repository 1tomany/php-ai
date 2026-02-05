<?php

namespace OneToMany\AI\Client\Gemini;

use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Request\File\UploadRequest;
use OneToMany\AI\Response\File\UploadResponse;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

use function ceil;
use function fread;
use function sprintf;
use function strlen;

final readonly class FileClient extends GeminiClient implements FileClientInterface
{
    /**
     * @see OneToMany\AI\Contract\Client\FileClientInterface
     *
     * @throws RuntimeException an empty file is uploaded
     * @throws RuntimeException a signed URL is not generated
     * @throws RuntimeException uploading a file chunk fails
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        // Files are uploaded in 8MB chunks
        $uploadChunkByteCount = 8 * 1024 * 1024;

        // Ensure the file can be opened and read before
        // doing anything that requires an HTTP request
        $fileHandle = $request->openFileHandle();

        // Calculate the total number of chunks needed to upload the entire file
        $uploadChunkCount = (int) ceil($request->getSize() / $uploadChunkByteCount);

        if (0 === $uploadChunkCount || 0 === $request->getSize()) {
            throw new RuntimeException('Empty files cannot be uploaded.');
        }

        try {
            // Generate a signed URL to upload the file to
            $url = $this->generateUrl('/upload/v1beta/files');

            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
                    'x-goog-api-key' => $this->apiKey,
                    'x-goog-upload-command' => 'start',
                    'x-goog-upload-protocol' => 'resumable',
                    'x-goog-upload-header-content-type' => $request->getFormat(),
                    'x-goog-upload-header-content-length' => $request->getSize(),
                ],
                'json' => [
                    'file' => [
                        'displayName' => $request->getName(),
                    ],
                ],
            ]);

            if (200 !== $statusCode = $response->getStatusCode()) {
                throw new RuntimeException(sprintf('Generating the signed URL failed: %s.', $this->decodeErrorResponse($response)->getInlineMessage()), $statusCode);
            }

            /** @var non-empty-string $uploadUrl */
            $uploadUrl = $response->getHeaders(true)['x-goog-upload-url'][0];

            // Counters to track progress
            $uploadChunk = $uploadOffset = 0;

            while ($fileChunk = fread($fileHandle, $uploadChunkByteCount)) {
                // Determine the command to let the server know if we're done uploading or not
                $uploadCommand = (++$uploadChunk >= $uploadChunkCount) ? 'upload, finalize' : 'upload';

                $response = $this->httpClient->request('POST', $uploadUrl, [
                    'headers' => [
                        'content-length' => $request->getSize(),
                        'x-goog-api-key' => $this->apiKey,
                        'x-goog-upload-offset' => $uploadOffset,
                        'x-goog-upload-command' => $uploadCommand,
                    ],
                    'body' => $fileChunk,
                ]);

                if (200 !== $statusCode = $response->getStatusCode()) {
                    throw new RuntimeException(sprintf('Chunk %d of %d was rejected by the server: %s.', $uploadChunk, $uploadChunkCount, $this->decodeErrorResponse($response)->getInlineMessage()), $statusCode);
                }

                // Don't assume the chunk was an even 8MB
                $uploadOffset = $uploadOffset + strlen($fileChunk);
            }

            /**
             * @var array{
             *   file: array{
             *     name: non-empty-string,
             *     displayName: non-empty-string,
             *     mimeType: non-empty-lowercase-string,
             *     sizeBytes: numeric-string,
             *     createTime: non-empty-string,
             *     updateTime: non-empty-string,
             *     expirationTime: non-empty-string,
             *     sha256Hash: non-empty-string,
             *     uri: non-empty-string,
             *     state: non-empty-uppercase-string,
             *     source: non-empty-uppercase-string,
             *   },
             * } $file
             */
            $file = $response->toArray(true);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        }

        return new UploadResponse($request->getModel(), $file['file']['uri'], $file['file']['name'], null, new \DateTimeImmutable($file['file']['expirationTime']));
    }
}
