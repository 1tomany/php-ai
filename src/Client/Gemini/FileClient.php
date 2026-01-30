<?php

namespace OneToMany\AI\Client\Gemini;

use OneToMany\AI\Client\Exception\ConnectingToHostFailedException;
use OneToMany\AI\Client\Exception\DecodingResponseContentFailedException;
use OneToMany\AI\Client\Gemini\Type\Error\Error;
use OneToMany\AI\Client\Gemini\Type\File\File;
use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Contract\Input\File\CacheFileInputInterface;
use OneToMany\AI\Contract\Response\File\CachedFileResponseInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Response\File\CachedFileResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface as HttpClientDecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface as HttpClientTransportExceptionInterface;

use function ceil;
use function fread;
use function sprintf;
use function strlen;

final readonly class FileClient extends BaseClient implements FileClientInterface
{
    /**
     * Files are uploaded in 8MB chunks.
     */
    public const int FILE_CHUNK_BYTES = 8 * 1024 * 1024;

    /**
     * The header that contains the signed upload URL.
     */
    public const string UPLOAD_URL_HEADER = 'x-goog-upload-url';

    public function cache(CacheFileInputInterface $request): CachedFileResponseInterface
    {
        $url = $this->generateUrl();

        try {
            // Generate a signed URL to upload the file to
            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
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

            $responseHeaders = $response->getHeaders(false);

            if (!isset($responseHeaders[self::UPLOAD_URL_HEADER])) {
                throw new RuntimeException(new Error($response->getContent(false))->message);
            }

            /** @var non-empty-string $uploadUrl */
            $uploadUrl = $responseHeaders[self::UPLOAD_URL_HEADER][0];
        } catch (HttpClientExceptionInterface $e) {
            throw new ConnectingToHostFailedException($url, $e);
        }

        try {
            // Counters to track progress
            $uploadChunk = $uploadOffset = 0;

            // The total number of chunks needed to complete the upload
            $uploadChunkCount = (int) ceil($request->getSize() / self::FILE_CHUNK_BYTES);

            // Open the file to read it
            $fileHandle = $request->open();

            while ($fileChunk = fread($fileHandle, self::FILE_CHUNK_BYTES)) {
                // Determine the command to let the server know if we're done uploading or not
                $uploadCommand = (++$uploadChunk >= $uploadChunkCount) ? 'upload, finalize' : 'upload';

                $response = $this->httpClient->request('POST', $uploadUrl, [
                    'headers' => [
                        'content-length' => $request->getSize(),
                        'x-goog-upload-offset' => $uploadOffset,
                        'x-goog-upload-command' => $uploadCommand,
                    ],
                    'body' => $fileChunk,
                ]);

                if (200 !== $response->getStatusCode()) {
                    throw new RuntimeException(sprintf('Caching the file "%s" failed because chunk %d of %d was rejected by the server.', $request->getName(), $uploadChunk, $uploadChunkCount), $response->getStatusCode(), new RuntimeException($response->getContent(false)));
                }

                // Can't always assume the chunk was an even 8MB
                $uploadOffset = $uploadOffset + strlen($fileChunk);
            }

            $file = $this->normalizer->denormalize($response->toArray(false), File::class, null, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[file]',
            ]);
        } catch (HttpClientTransportExceptionInterface $e) {
            throw new ConnectingToHostFailedException($uploadUrl, $e);
        } catch (HttpClientDecodingExceptionInterface|SerializerExceptionInterface $e) {
            throw new DecodingResponseContentFailedException(sprintf('Caching the file "%s"', $request->getName()), $e);
        }

        return new CachedFileResponse($request->getVendor(), $file->uri, $file->name, $request->getFormat(), null, $file->expirationTime);
    }

    /**
     * @return non-empty-string
     */
    private function generateUrl(): string
    {
        return 'https://generativelanguage.googleapis.com/upload/v1beta/files';
    }
}
