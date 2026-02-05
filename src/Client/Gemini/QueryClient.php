<?php

namespace OneToMany\AI\Client\Gemini;

use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

use function in_array;
use function sprintf;

final readonly class QueryClient extends BaseClient implements QueryClientInterface
{
    use ExceptionTrait;

    /**
     * @see App\File\Vendor\AI\Contract\Client\QueryClientInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $requestContent = [
            'contents' => [],
        ];

        foreach ($request->getComponents() as $component) {
            if ($component instanceof TextComponent) {
                if ($component->getRole()->isSystem()) {
                    $requestContent['systemInstruction'] = [
                        'parts' => [
                            [
                                'text' => $component->getText(),
                            ],
                        ],
                    ];
                }

                if ($component->getRole()->isUser()) {
                    $requestContent['contents'][] = [
                        'parts' => [
                            [
                                'text' => $component->getText(),
                            ],
                        ],
                    ];
                }
            }

            if ($component instanceof FileUriComponent) {
                $requestContent['contents'][] = [
                    'parts' => [
                        [
                            'fileData' => [
                                'fileUri' => $component->getUri(),
                            ],
                        ],
                    ],
                ];
            }

            if ($component instanceof SchemaComponent) {
                $requestContent['generationConfig'] = [
                    'responseMimeType' => $component->getFormat(),
                    'responseJsonSchema' => $component->getSchema(),
                ];
            }
        }

        return new CompileResponse($request->getModel(), $this->generateUrl($request->getModel()), $requestContent);
    }

    /**
     * @see App\File\Vendor\AI\Contract\Client\QueryClientInterface
     */
    public function execute(ExecuteRequest $request): ExecuteResponse
    {
        $timer = new Stopwatch(true)->start('execute');

        try {
            $response = $this->httpClient->request('POST', $request->getUrl(), [
                'json' => $request->getRequest(),
            ]);

            /**
             * @var array{
             *   candidates: non-empty-list<
             *     array{
             *       content: array{
             *         parts: non-empty-list<
             *           array{
             *             text: non-empty-string,
             *           },
             *         >,
             *         role: 'model',
             *       },
             *       finishReason: non-empty-uppercase-string,
             *       index: non-negative-int,
             *     },
             *   >,
             *   usageMetadata: array{
             *     promptTokenCount?: non-negative-int,
             *     candidatesTokenCount?: non-negative-int,
             *     totalTokenCount?: non-negative-int,
             *     thoughtsTokenCount?: non-negative-int,
             *   },
             *   modelVersion: non-empty-lowercase-string,
             *   responseId: non-empty-string,
             * } $responseContent
             */
            $responseContent = $response->toArray(true);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        }

        return new ExecuteResponse($request->getModel(), $responseContent['responseId'], $responseContent['candidates'][0]['content']['parts'][0]['text'], $responseContent, $timer->stop()->getDuration());
    }

    /**
     * @see App\File\Vendor\AI\Contract\Client\ModelClientInterface
     */
    public function supportsRequest(object $request): bool
    {
        return ($request instanceof CompileRequest || $request instanceof ExecuteRequest) && in_array($request->getModel(), $this->getSupportedModels());
    }

    /**
     * @param non-empty-string $model
     *
     * @return non-empty-string
     */
    private function generateUrl(string $model): string
    {
        return sprintf('https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent', $model);
    }
}
