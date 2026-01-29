<?php

use OneToMany\AI\Client\Gemini\FileClient;
use OneToMany\AI\Client\Gemini\PromptClient;
use OneToMany\AI\Contract\Exception\ExceptionInterface as AiExceptionInterface;
use OneToMany\AI\Request\File\CacheFileRequest;
use OneToMany\AI\Request\Prompt\CompilePromptRequest;
use OneToMany\AI\Request\Prompt\Content\CachedFile;
use OneToMany\AI\Request\Prompt\Content\InputText;
use OneToMany\AI\Request\Prompt\Content\JsonSchema;
use OneToMany\AI\Request\Prompt\DispatchPromptRequest;
use Symfony\Component\HttpClient\HttpClient;

require_once __DIR__.'/../bootstrap.php';

$keyVar = 'GEMINI_API_KEY';

if (!$googApiKey = getenv($keyVar)) {
    printf("Set the %s environment variable to continue.\n", $keyVar);
    exit(1);
}

if (!is_string($argv[1] ?? null)) {
    printf("Usage: php %s <file-path>\n", basename(__FILE__));
    exit(1);
}

$path = realpath($argv[1]);

if (!$path || !is_file($path) || !is_readable($path)) {
    $path = realpath(__DIR__.'/../files/water-heater-label.jpeg');
}

// Construct the HTTP Client
$httpClient = HttpClient::create([
    'headers' => [
        'accept' => 'application/json',
        'x-goog-api-key' => $googApiKey,
    ],
]);

$serializer = createSerializer();

try {
    // Construct the Gemini FileClient
    $fileClient = new FileClient(null, $httpClient, $serializer);

    // Create a request to cache the file with Gemini
    $cacheFileRequest = CacheFileRequest::create('gemini', $path);

    // Cache the file with Gemini with the FileClient
    $cachedFileResponse = $fileClient->cache($cacheFileRequest);

    printf("File '%s' successfully cached with Gemini!\n", $cacheFileRequest->getName());
    printf("URI: %s\n", $cachedFileResponse->getUri());
    printf("Name: %s\n", $cachedFileResponse->getName());

    if ($expiresAt = $cachedFileResponse->getExpiresAt()) {
        printf("ExpiresAt: %s\n", $expiresAt->format('c'));
    }

    printf("%s\n", str_repeat('-', 60));

    // Construct the Gemini PromptClient
    $promptClient = new PromptClient(null, $httpClient, $serializer);

    // Create the individual components of the prompt
    $promptContentSystemText = InputText::system(implode("\n", [
        '1. Select a tag that most accurately describes the file.',
        '2. Write a short five to ten word summary of the file.',
    ]));

    $promptConentCachedFile = new CachedFile(
        $cachedFileResponse->getUri(),
        $cachedFileResponse->getFormat(),
    );

    $promptContentJsonSchema = new JsonSchema('identity', [
        'title' => 'identity',
        'type' => 'object',
        'properties' => [
            'tag' => [
                'type' => 'string',
                'enum' => [
                    'job:notes',
                    'machinery:label',
                    'payment:card',
                    'payment:check',
                    'payment:cash',
                    'sales:invoice',
                    'sales:receipt',
                    'other',
                ],
                'description' => 'A label that most accurately describes the file',
            ],
            'summary' => [
                'type' => 'string',
                'description' => 'A five to ten word summary of the file',
            ],
        ],
        'propertyOrdering' => [
            'tag',
            'summary',
        ],
        'required' => [
            'tag',
            'summary',
        ],
        'additionalProperties' => false,
    ]);

    // Create a request to compile the prompt into a Gemini request body
    $compilePromptRequest = new CompilePromptRequest('gemini', 'gemini-2.5-flash-lite', [
        $promptContentSystemText, $promptConentCachedFile, $promptContentJsonSchema,
    ]);

    // Compile the prompt into a Gemini request
    $compiledPromptResponse = $promptClient->compile(...[
        'request' => $compilePromptRequest,
    ]);

    // Convert the compiled prompt to a dispatchable request
    $dispatchPromptRequest = DispatchPromptRequest::create(...[
        'response' => $compiledPromptResponse,
    ]);

    $dispatchedPromptResponse = $promptClient->dispatch(...[
        'request' => $dispatchPromptRequest,
    ]);

    print_r($dispatchedPromptResponse);
} catch (AiExceptionInterface $e) {
    printf("[ERROR] %s\n", $e->getMessage());
    exit(1);
}
