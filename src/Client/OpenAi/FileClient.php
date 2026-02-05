<?php

namespace OneToMany\AI\Client\OpenAi;

use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Request\File\UploadRequest;
use OneToMany\AI\Response\File\UploadResponse;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

final readonly class FileClient extends OpenAiClient implements FileClientInterface
{
    /**
     * @see OneToMany\AI\Contract\Client\FileClientInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        $url = $this->generateUrl('files');

        try {
            $response = $this->httpClient->request('POST', $url, [
                'auth_bearer' => $this->apiKey,
                'body' => [
                    'file' => $request->openFileHandle(),
                    'purposes' => $request->getPurpose(),
                ],
            ]);

            /**
             * @var array{
             *   id: non-empty-string,
             *   object: 'file',
             *   bytes: non-negative-int,
             *   created_at: non-negative-int,
             *   expires_at: ?non-negative-int,
             *   filename: non-empty-string,
             *   purpose: non-empty-lowercase-string,
             * } $file
             */
            $file = $response->toArray(true);

            print_r($file);
            // if (200 !== $response->getStatusCode() || isset($responseContent['error'])) {
            //     $error = $this->denormalizer->denormalize($responseContent, Error::class, null, [
            //         UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
            //     ]);

            //     throw new RuntimeException($error->message);
            // }

            // $file = $this->denormalizer->denormalize($responseContent, File::class);
        } catch (HttpClientExceptionInterface $e) {
            // $this->handleHttpException($e);
        }

        return new UploadResponse($request->getModel(), $file['id'], $file['filename'], $file['purpose'], null !== $file['expires_at'] ? \DateTimeImmutable::createFromTimestamp($file['expires_at']) : null);
    }
}
