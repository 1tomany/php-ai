<?php

use OneToMany\AI\Client\Gemini\FileClient;
use OneToMany\AI\Contract\Exception\ExceptionInterface as AiExceptionInterface;
use OneToMany\AI\Request\File\CacheFileRequest;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\PropertyInfo\Extractor\ConstructorExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\Serializer;

require_once __DIR__.'/../../vendor/autoload.php';

$keyVar = 'GEMINI_API_KEY';

if (!$googApiKey = getenv($keyVar)) {
    printf("Set the %s environment variable to continue.\n", $keyVar);
    exit(1);
}

if (!isset($argv[1]) || !is_string($argv[1])) {
    printf("Usage: php %s <file-path>\n", basename(__FILE__));
    exit(1);
}

$path = realpath($argv[1]);

if (!is_file($path) || !is_readable($path)) {
    printf("The file '%s' is not a file or not readable.\n", $path);
    exit(1);
}

$httpClient = HttpClient::create([
    'headers' => [
        'accept' => 'application/json',
        'x-goog-api-key' => $googApiKey,
    ],
]);

$propertyInfoExtractor = new PropertyInfoExtractor([], [
    new ConstructorExtractor([new PhpDocExtractor()]),
]);

$serializer = new Serializer(
    normalizers: [
        new ArrayDenormalizer(),
        new BackedEnumNormalizer(),
        new DateTimeNormalizer(),
        new UnwrappingDenormalizer(),

        // This normalizer must come last to ensure the UnwrappingDenormalizer can be used
        new ObjectNormalizer(propertyInfoExtractor: $propertyInfoExtractor),
    ],
);

$fileClient = new FileClient($httpClient, $serializer);

try {
    $cachedFile = $fileClient->cache(CacheFileRequest::create('gemini', $path));
} catch (AiExceptionInterface $e) {
    printf("[ERROR] %s\n", $e->getPrevious()->getMessage());
    exit(1);
}

print_r($cachedFile);
