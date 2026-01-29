<?php

namespace OneToMany\AI\Client\Gemini;

use OneToMany\AI\Client\Exception\ConnectingToHostFailedException;
use OneToMany\AI\Client\Exception\DecodingResponseContentFailedException;
use OneToMany\AI\Client\Gemini\Type\Content\GenerateContentResponse;
use OneToMany\AI\Client\Gemini\Type\Error\Status;
use OneToMany\AI\Contract\Client\PromptClientInterface;
use OneToMany\AI\Contract\Request\Prompt\DispatchPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Response\Prompt\DispatchedPromptResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface as HttpClientDecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface as HttpClientTransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function sprintf;

final readonly class PromptClient implements PromptClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private DenormalizerInterface $denormalizer,
    ) {
    }

    /**
     * @see OneToMany\AI\Contract\Client\PromptClientInterface
     */
    public function dispatch(DispatchPromptRequestInterface $request): DispatchedPromptResponseInterface
    {
        $timer = new Stopwatch(true)->start('dispatch');

        try {
            $url = $this->generateUrl($request->getModel());

            // Generate a signed URL to upload the file to
            $response = $this->httpClient->request('POST', $url, [
                'json' => $request->getRequest(),
            ]);

            /** @var array<string, mixed> $responseContent */
            $responseContent = $response->toArray(false);

            if (200 !== $response->getStatusCode()) {
                $status = $this->denormalizer->denormalize($responseContent, Status::class, null, [
                    UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
                ]);

                throw new RuntimeException($status->message, $status->code);
            }

            $generateContentResponse = $this->denormalizer->denormalize($responseContent, GenerateContentResponse::class);
        } catch (HttpClientTransportExceptionInterface $e) {
            throw new ConnectingToHostFailedException($url, $e);
        } catch (HttpClientDecodingExceptionInterface|SerializerExceptionInterface $e) {
            throw new DecodingResponseContentFailedException('Dispatching the prompt', $e);
        }

        return new DispatchedPromptResponse($request->getVendor(), $request->getModel(), $generateContentResponse->responseId, $generateContentResponse->getOutput(), $responseContent, $timer->stop()->getDuration());
    }

    /**
     * @param non-empty-lowercase-string $model
     *
     * @return non-empty-string
     */
    private function generateUrl(string $model): string
    {
        return sprintf('https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent', $model);
    }
}
