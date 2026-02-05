<?php

namespace OneToMany\AI\Client\Mock;

use OneToMany\AI\Contract\Client\QueryClientInterface;
use OneToMany\AI\Request\Query\CompileRequest;
use OneToMany\AI\Request\Query\Component\FileUriComponent;
use OneToMany\AI\Request\Query\Component\SchemaComponent;
use OneToMany\AI\Request\Query\Component\TextComponent;
use OneToMany\AI\Request\Query\ExecuteRequest;
use OneToMany\AI\Response\Query\CompileResponse;
use OneToMany\AI\Response\Query\ExecuteResponse;

use function in_array;
use function json_encode;
use function random_int;

final readonly class QueryClient extends BaseClient implements QueryClientInterface
{
    /**
     * @see OneToMany\AI\Contract\Client\QueryClientInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $requestContent = [
            'model' => $request->getModel(),
        ];

        foreach ($request->getComponents() as $component) {
            if ($component instanceof TextComponent) {
                $requestContent['contents'][] = [
                    'text' => $component->getText(),
                    'role' => $component->getRole()->getValue(),
                ];
            }

            if ($component instanceof FileUriComponent) {
                $requestContent['contents'][] = [
                    'fileUri' => $component->getUri(),
                ];
            }

            if ($component instanceof SchemaComponent) {
                $requestContent['schema'] = [
                    'name' => $component->getName(),
                    'schema' => $component->getSchema(),
                    'format' => $component->getFormat(),
                ];
            }
        }

        return new CompileResponse($request->getModel(), 'https://mock-llm.service/api/generate', $requestContent);
    }

    /**
     * @see OneToMany\AI\Contract\Client\QueryClientInterface
     */
    public function execute(ExecuteRequest $request): ExecuteResponse
    {
        $uri = $this->generateUri('query');

        /** @var non-empty-string $output */
        $output = json_encode(['tag' => $this->faker->word(), 'summary' => $this->faker->sentence(10)]);

        return new ExecuteResponse($request->getModel(), $uri, $output, ['id' => $uri, 'output' => $output], random_int(100, 10000));
    }

    /**
     * @see OneToMany\AI\Contract\Client\ClientInterface
     */
    public function supportsRequest(object $request): bool
    {
        return ($request instanceof CompileRequest || $request instanceof ExecuteRequest) && in_array($request->getModel(), $this->getSupportedModels());
    }
}
