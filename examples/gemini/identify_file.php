<?php

use OneToMany\AI\Client\Gemini\FileClient;
use OneToMany\AI\Client\Gemini\PromptClient;
use OneToMany\AI\Client\Gemini\QueryClient;
use OneToMany\AI\Contract\Exception\ExceptionInterface as AiExceptionInterface;
use OneToMany\AI\Request\File\UploadRequest;
use OneToMany\AI\Request\Prompt\CompilePromptRequest;
use OneToMany\AI\Request\Prompt\Content\CachedFile;
use OneToMany\AI\Request\Prompt\Content\InputText;
use OneToMany\AI\Request\Prompt\Content\JsonSchema;
use OneToMany\AI\Request\Prompt\DispatchPromptRequest;
use OneToMany\AI\Request\Query\CompileRequest;
use Symfony\Component\HttpClient\HttpClient;

require_once __DIR__.'/../../vendor/autoload.php';

$keyVar = 'GEMINI_API_KEY';

if (!$googApiKey = getenv($keyVar)) {
    printf("Set the %s environment variable to continue.\n", $keyVar);
    exit(1);
}

$path = realpath($argv[1] ?? __DIR__.'/../files/water-heater-label.jpeg');

if (!$path || !is_file($path) || !is_readable($path)) {
    printf("The file '%s' does not exist or not readable.\n", $path);
    exit(1);
}

// Construct the HTTP Client
$httpClient = HttpClient::create([
    'headers' => [
        'accept' => 'application/json',
        'x-goog-api-key' => $googApiKey,
    ],
]);

try {
    // The client to upload files
    $fileClient = new FileClient($httpClient);

    // Create a request to upload the file
    $uploadRequest = new UploadRequest(...[
        'model' => 'gemini-2.5-flash',
    ]);

    $uploadRequest->atPath($path)->withFormat(...[ // @phpstan-ignore-line
        'format' => mime_content_type($path),
    ]);

    // Upload the file to Gemini with the FileClient
    $response = $fileClient->upload($uploadRequest);

    // Output the upload results
    printf("File successfully uploaded!\n\n");
    printf("URI: %s\n", $response->getUri());
    printf("Name: %s\n", $response->getName());

    if ($expiresAt = $response->getExpiresAt()) {
        printf("ExpiresAt: %s\n", $expiresAt->format('c'));
    }

    printf("%s\n", str_repeat('-', 60));

    // The client to compile and execute queries
    $queryClient = new QueryClient($httpClient);

    // Compile the query to send to Gemini
    $compileRequest = new CompileRequest(...[
        'model' => $response->getModel(),
    ]);

    $compileRequest->withFileUri(...[
        'fileUri' => $response->getUri(),
    ]);

    $compileRequest->usingSchema([
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

    $compileRequest->withSystemText(implode("\n", [
        '1. Select a tag that most accurately describes the file.',
        '2. Write a short five to ten word summary of the file.',
    ]));

    $response = $queryClient->compile($compileRequest);

    /*
    // Convert the compiled prompt to a dispatchable request
    $dispatchPromptRequest = DispatchPromptRequest::create(...[
        'response' => $compiledPromptResponse,
    ]);

    $dispatchedPromptResponse = $queryClient->dispatch(...[
        'request' => $dispatchPromptRequest,
    ]);

    printf("Prompt successfully dispatched!\n\n");
    printf("URI: %s\n", $dispatchedPromptResponse->getUri());
    printf("Runtime: %sms\n", $dispatchedPromptResponse->getRuntime());

    if (null !== $output = $dispatchedPromptResponse->getOutput()) {
        printf("Output: %s\n", json_encode(json_decode($output, true)));
    }

    printf("%s\n", str_repeat('-', 60));
    */

    print_r($response);
} catch (AiExceptionInterface $e) {
    printf("[ERROR] %s\n", $e->getMessage());
    exit(1);
}
