<?php

require_once __DIR__.'/vendor/autoload.php';

$httpClient = Symfony\Component\HttpClient\HttpClient::create();
$fileClient = new OneToMany\AI\Client\OpenAi\FileClient($httpClient, getenv('OPENAI_API_KEY'));

try {
    $uploadRequest = new OneToMany\AI\Request\File\UploadRequest('gpt-5-mini')
        ->atPath('/Users/vic/Downloads/att_56194.jpeg')
        ->withFormat('image/jpeg')
        ->withPurpose('user-data');

    $response = $fileClient->upload($uploadRequest);
    print_r($response);
} catch (\Exception $e) {
    printf("[ERROR] %s\n", $e->getMessage());
}
