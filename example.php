<?php

use OneToMany\AI\Bridge\Gemini\Normalizer\QueryNormalizer as GeminiQueryNormalizer;
use OneToMany\AI\Bridge\Gemini\SearchStoreProvider as GeminiSearchStoreProvider;
use OneToMany\AI\Bridge\OpenAI\Normalizer\QueryNormalizer as OpenAiQueryNormalizer;
use OneToMany\AI\Bridge\OpenAI\SearchStoreProvider as OpenAiSearchStoreProvider;
use OneToMany\AI\Bridge\Transport;
use OneToMany\AI\Resource\SearchStoreFiles;
use OneToMany\AI\Resource\SearchStores;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\PropertyInfo\Extractor\ConstructorExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpStanExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\Serializer;

require_once __DIR__.'/vendor/autoload.php';

$typeExtractor = new PropertyInfoExtractor([], [
    new ConstructorExtractor([
        new PhpDocExtractor(),
        new PhpStanExtractor(),
        new ReflectionExtractor(),
    ]),
]);

$normalizers = [
    new BackedEnumNormalizer(),
    new DateTimeNormalizer(),
    new ArrayDenormalizer(),
    new UnwrappingDenormalizer(),
    new GeminiQueryNormalizer(),
    new OpenAiQueryNormalizer(),
    new ObjectNormalizer(null, null, null, $typeExtractor),
];

$serializer = new Serializer($normalizers, [
    new JsonEncoder(), new XmlEncoder(),
]);

$httpClient = HttpClient::create([
    'timeout' => 60.0,
]);

$transport = new Transport($httpClient, $serializer);

$providers = [];

if ($apiKey = getenv('GEMINI_API_KEY')) {
    $providers[] = new GeminiSearchStoreProvider(
        $transport, $serializer, apiKey: $apiKey,
    );
}

if ($apiKey = getenv('OPENAI_API_KEY')) {
    $providers[] = new OpenAiSearchStoreProvider(
        $transport, $serializer, apiKey: $apiKey,
    );
}


$searchStores = new SearchStores($providers, new SearchStoreFiles($providers));

// $searchStore = $searchStores->create('gemini', 'Test Store');
// print_r($searchStore);

$searchStoreFile = $searchStores->files->attach('gemini', 'fileSearchStores/test-store-415w96nqzf97', 'files/kiji5x88e8eu');
print_r($searchStoreFile);
