<?php

namespace OneToMany\AI\Client\OpenAi;

use OneToMany\AI\Client\OpenAi\Type\Response\Enum\FileType;
use OneToMany\AI\Contract\Client\QueryClientInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Request\Query\CompileRequest;
use OneToMany\AI\Request\Query\Component\FileUriComponent;
use OneToMany\AI\Request\Query\Component\SchemaComponent;
use OneToMany\AI\Request\Query\Component\TextComponent;
use OneToMany\AI\Request\Query\ExecuteRequest;
use OneToMany\AI\Response\Query\CompileResponse;
use OneToMany\AI\Response\Query\ExecuteResponse;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

/**
 * @phpstan-type OpenAiContentText array{type: 'output_text', text: non-empty-string}
 * @phpstan-type OpenAiContentReasoning array{type: 'reasoning_text', text: non-empty-string}
 * @phpstan-type OpenAiContentRefusal array{type: 'refusal', refusal: non-empty-string}
 * @phpstan-type OpenAiOutputMessage array{
 *   type: 'message',
 *   id: non-empty-string,
 *   status: 'in_progress'|'completed'|'incomplete',
 *   content: non-empty-list<OpenAiContentText|OpenAiContentRefusal>,
 * }
 * @phpstan-type OpenAiOutputReasoning array{
 *   type: 'reasoning',
 *   id: non-empty-string,
 *   status: 'in_progress'|'completed'|'incomplete',
 *   content: non-empty-list<OpenAiContentReasoning>,
 * }
 */
final readonly class QueryClient extends OpenAiClient implements QueryClientInterface
{
    /**
     * @see OneToMany\AI\Contract\Client\QueryClientInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $requestContent = [
            'model' => $request->getModel(),
            'input' => [],
        ];

        foreach ($request->getComponents() as $component) {
            if ($component instanceof TextComponent) {
                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => $component->getText(),
                        ],
                    ],
                    'role' => $component->getRole()->getValue(),
                ];
            }

            if ($component instanceof FileUriComponent) {
                $fileType = FileType::create(...[
                    'format' => $component->getFormat(),
                ]);

                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => $fileType->getValue(),
                            'file_id' => $component->getUri(),
                        ],
                    ],
                    'role' => $component->getRole()->getValue(),
                ];
            }

            if ($component instanceof SchemaComponent) {
                $requestContent['text'] = [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => $component->getName(),
                        'schema' => $component->getSchema(),
                        'strict' => $component->isStrict(),
                    ],
                ];
            }
        }

        return new CompileResponse($request->getModel(), $this->generateUrl('responses'), $requestContent);
    }

    /**
     * @see OneToMany\AI\Contract\Client\QueryClientInterface
     */
    public function execute(ExecuteRequest $request): ExecuteResponse
    {
        $timer = new Stopwatch(true)->start('execute');

        try {
            $response = $this->httpClient->request('POST', $request->getUrl(), [
                'auth_bearer' => $this->apiKey,
                'json' => $request->getRequest(),
            ]);

            /**
             * @var array{
             *   id: non-empty-string,
             *   object: 'response',
             *   created_at: non-negative-int,
             *   status: 'completed'|'failed'|'in_progress'|'cancelled'|'queued'|'incomplete',
             *   completed_at?: non-negative-int,
             *   error: ?array{
             *     code: non-empty-string,
             *     message: non-empty-string,
             *   },
             *   incomplete_details: ?array{
             *     reason: non-empty-string,
             *   },
             *   max_output_tokens: ?non-negative-int,
             *   model: non-empty-lowercase-string,
             *   output?: non-empty-list<OpenAiOutputMessage|OpenAiOutputReasoning>,
             *   usage: array{
             *     input_tokens: non-negative-int,
             *     input_tokens_details?: array{
             *       cached_tokens: non-negative-int,
             *     },
             *     output_tokens: non-negative-int,
             *     output_tokens_details?: array{
             *       reasoning_tokens: non-negative-int,
             *     },
             *     total_tokens: non-negative-int,
             *   },
             * } $output
             */
            $output = $response->toArray(true);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        }

        if (isset($output['error'])) {
            throw new RuntimeException($output['error']['message']);
        }

        if (!isset($output['output'])) {
            throw new RuntimeException('The query failed to generate any output.');
        }

        return new ExecuteResponse($request->getModel(), $output['id'], '', $output, $timer->stop()->getDuration());
    }
}
