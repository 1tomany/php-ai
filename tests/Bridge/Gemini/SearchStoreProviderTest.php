<?php

namespace OneToMany\AI\Tests\Bridge\Gemini;

use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Document;
use OneToMany\AI\Bridge\Gemini\SearchStoreProvider;
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
                'https://generativelanguage.googleapis.com/v1beta/fileSearchStores/docs',
                [
                    'headers' => ['x-goog-api-key' => 'api-key'],
                    'query' => ['force' => 'true'],
                ],
            )
            ->willReturn($response);

        $this->provider->delete('fileSearchStores/docs');
    }

    public function testReadSearchStoreFile(): void
    {
        $response = new MockResponse();
        $record = new Document(
            'fileSearchStores/docs/documents/guide',
            'STATE_ACTIVE',
            [
                ['key' => '__onetomany_ai_file_id', 'stringValue' => 'files/guide'],
                ['key' => 'section', 'stringValue' => 'reference'],
            ],
        );

        $this->transport
            ->expects($this->once())
            ->method('getRequest')
            ->with(
                'https://generativelanguage.googleapis.com/v1beta/fileSearchStores/docs/documents/guide',
                ['headers' => ['x-goog-api-key' => 'api-key']],
            )
            ->willReturn($response);
        $this->transport
            ->expects($this->once())
            ->method('decode')
            ->with($response, Document::class)
            ->willReturn($record);

        $file = $this->provider->readFile(
            'fileSearchStores/docs',
            'fileSearchStores/docs/documents/guide',
        );

        $this->assertSame('fileSearchStores/docs/documents/guide', $file->id);
        $this->assertSame('fileSearchStores/docs', $file->searchStoreId);
        $this->assertSame('files/guide', $file->fileId);
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
                'https://generativelanguage.googleapis.com/v1beta/fileSearchStores/docs/documents/guide',
                [
                    'headers' => ['x-goog-api-key' => 'api-key'],
                    'query' => ['force' => 'true'],
                ],
            )
            ->willReturn($response);

        $this->provider->deleteFile(
            'fileSearchStores/docs',
            'fileSearchStores/docs/documents/guide',
        );
    }
}
