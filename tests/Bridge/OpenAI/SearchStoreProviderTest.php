<?php

namespace OneToMany\AI\Tests\Bridge\OpenAI;

use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\VectorStoreFile;
use OneToMany\AI\Bridge\OpenAI\SearchStoreProvider;
use OneToMany\AI\Bridge\Transport;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Serializer;

use function implode;

#[Group('UnitTests')]
final class SearchStoreProviderTest extends TestCase
{
    private Transport&MockObject $transport;
    private SearchStoreProvider $provider;

    protected function setUp(): void
    {
        $this->transport = $this->createMock(Transport::class);
        $this->transport->method('url')->willReturnCallback(static fn (string ...$parts): string => implode('/', $parts));
        $this->provider = new SearchStoreProvider($this->transport, new Serializer(), 'api-key');
    }

    public function testDeleteSearchStore(): void
    {
        $response = new MockResponse();

        $this->transport
            ->expects($this->once())
            ->method('deleteRequest')
            ->with(
                'https://api.openai.com/v1/vector_stores/vs_123',
                [
                    'auth_bearer' => 'api-key',
                    'headers' => ['OpenAI-Beta' => 'assistants=v2'],
                ],
            )
            ->willReturn($response);

        $this->provider->delete('vs_123');
    }

    public function testReadSearchStoreFile(): void
    {
        $response = new MockResponse();
        $record = new VectorStoreFile('file_123', 'vs_123', 'completed', ['section' => 'reference']);

        $this->transport
            ->expects($this->once())
            ->method('getRequest')
            ->with(
                'https://api.openai.com/v1/vector_stores/vs_123/files/file_123',
                [
                    'auth_bearer' => 'api-key',
                    'headers' => ['OpenAI-Beta' => 'assistants=v2'],
                ],
            )
            ->willReturn($response);
        $this->transport
            ->expects($this->once())
            ->method('decode')
            ->with($response, VectorStoreFile::class)
            ->willReturn($record);

        $file = $this->provider->readFile('vs_123', 'file_123');

        $this->assertSame('file_123', $file->id);
        $this->assertSame('vs_123', $file->searchStoreId);
        $this->assertSame('file_123', $file->fileId);
        $this->assertSame('completed', $file->status);
        $this->assertSame(['section' => 'reference'], $file->metadata);
    }

    public function testDeleteSearchStoreFile(): void
    {
        $response = new MockResponse();

        $this->transport
            ->expects($this->once())
            ->method('deleteRequest')
            ->with(
                'https://api.openai.com/v1/vector_stores/vs_123/files/file_123',
                [
                    'auth_bearer' => 'api-key',
                    'headers' => ['OpenAI-Beta' => 'assistants=v2'],
                ],
            )
            ->willReturn($response);

        $this->provider->deleteFile('vs_123', 'file_123');
    }
}
