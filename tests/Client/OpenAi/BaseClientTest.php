<?php

namespace OneToMany\AI\Tests\Client\OpenAi;

use OneToMany\AI\Client\OpenAi\BaseClient;
use OneToMany\AI\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;

final class BaseClientTest extends TestCase
{
    public function testConstructingClientRequiresGeminiApiKeyOrScopedHttpClient(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Constructing an OpenAI client requires either an API key or scoped HTTP client, but neither were provided.');

        $this->getMockBuilder(BaseClient::class)->setConstructorArgs([null, null, new ArrayDenormalizer()])->getMock();
    }
}
