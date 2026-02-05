<?php

namespace OneToMany\AI\Client\Gemini;

use OneToMany\AI\Contract\Client\QueryClientInterface;
use OneToMany\AI\Request\Query\CompileRequest;
use OneToMany\AI\Request\Query\Component\FileUriComponent;
use OneToMany\AI\Request\Query\Component\SchemaComponent;
use OneToMany\AI\Request\Query\Component\TextComponent;
use OneToMany\AI\Request\Query\ExecuteRequest;
use OneToMany\AI\Response\Query\CompileResponse;
use OneToMany\AI\Response\Query\ExecuteResponse;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

use function sprintf;

final readonly class QueryClient extends GeminiClient implements QueryClientInterface
{
    /**
     * @see OneToMany\AI\Contract\Client\QueryClientInterface
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
     * @see OneToMany\AI\Contract\Client\QueryClientInterface
     */
    public function execute(ExecuteRequest $request): ExecuteResponse
    {
        $timer = new Stopwatch(true)->start('execute');

        try {
            $response = $this->httpClient->request('POST', $request->getUrl(), [
                'headers' => [
                    'x-goog-api-key' => $this->apiKey,
                ],
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
     * @param non-empty-string $model
     *
     * @return non-empty-string
     */
    protected function generateUrl(string $model): string
    {
        return parent::generateUrl(sprintf('/v1beta/models/%s:generateContent', $model));
    }
}
