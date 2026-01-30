<?php

namespace OneToMany\AI\Client\OpenAi;

use OneToMany\AI\Client\Exception\ConnectingToHostFailedException;
use OneToMany\AI\Client\Exception\DecodingResponseContentFailedException;
use OneToMany\AI\Client\OpenAi\Type\Error\Error;
use OneToMany\AI\Client\OpenAi\Type\File\File;
use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Contract\Input\File\CacheFileInputInterface;
use OneToMany\AI\Contract\Response\File\CachedFileResponseInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Response\File\CachedFileResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface as HttpClientDecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface as HttpClientTransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function sprintf;
use function stream_context_set_option;

final readonly class FileClient implements FileClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private DenormalizerInterface $denormalizer,
    ) {
    }

    /**
     * @see OneToMany\AI\Contract\Client\FileClientInterface
     */
    public function cache(CacheFileInputInterface $request): CachedFileResponseInterface
    {
        $fileHandle = $request->open();

        // This avoids Symfony using a MimeTypeGuesser since we already know the file format
        if (!stream_context_set_option($fileHandle, 'http', 'content_type', $request->getFormat())) {
            throw new RuntimeException(sprintf('Setting the content type to "%s" for the file "%s" failed.', $request->getFormat(), $request->getName()));
        }

        $url = $this->generateUrl('files');

        try {
            $response = $this->httpClient->request('POST', $url, [
                'body' => [
                    'file' => $fileHandle,
                    'purpose' => $request->getPurpose(),
                ],
            ]);

            $responseContent = $response->toArray(false);

            if (200 !== $response->getStatusCode() || isset($responseContent['error'])) {
                $error = $this->denormalizer->denormalize($responseContent, Error::class, null, [
                    UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
                ]);

                throw new RuntimeException($error->message);
            }

            $file = $this->denormalizer->denormalize($responseContent, File::class);
        } catch (HttpClientTransportExceptionInterface $e) {
            throw new ConnectingToHostFailedException($url, $e);
        } catch (HttpClientDecodingExceptionInterface|SerializerExceptionInterface $e) {
            throw new DecodingResponseContentFailedException(sprintf('Caching the file "%s"', $request->getName()), $e);
        }

        return new CachedFileResponse($request->getVendor(), $file->id, $file->filename, $request->getFormat(), $file->purpose, $file->getExpiresAt());
    }

    /**
     * @param non-empty-string $path
     *
     * @return non-empty-string
     */
    private function generateUrl(string $path): string
    {
        return sprintf('https://api.openai.com/v1/%s', $path);
    }
}
