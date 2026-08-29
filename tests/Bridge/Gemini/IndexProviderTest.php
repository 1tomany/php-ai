<?php

namespace OneToMany\AI\Tests\Bridge\Gemini;

use OneToMany\AI\Bridge\Gemini\IndexProvider;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Document;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Enum\DocumentState;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\ImportFileOperation;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\ImportFileResponse;
use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\ImportFileStatus;
use OneToMany\AI\Bridge\Transport;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Resource\Index\Enum\FileState;
use OneToMany\AI\Resource\Shared\Metadata;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\ResponseInterface as HttpResponseInterface;

use function implode;

#[Group('UnitTests')]
#[Group('BridgeTests')]
#[Group('GeminiTests')]
final class IndexProviderTest extends TestCase
{
    public function testAttachingFileWaitsForOperationAndReadsImportedDocument(): void
    {
        $importResponse = $this->createStub(HttpResponseInterface::class);
        $operationResponse = $this->createStub(HttpResponseInterface::class);
        $documentResponse = $this->createStub(HttpResponseInterface::class);

        $transport = $this->createTransport();
        $transport
            ->expects($this->once())
            ->method('postRequest')
            ->with('https://generativelanguage.googleapis.com/v1beta/fileSearchStores/store_123:importFile', [
                'headers' => [
                    'x-goog-api-key' => 'api-key',
                ],
                'json' => [
                    'fileName' => 'files/file_123',
                ],
            ])
            ->willReturn($importResponse)
        ;

        $request = 0;
        $transport
            ->expects($this->exactly(2))
            ->method('getRequest')
            ->willReturnCallback(static function (string $url, array $options) use (&$request, $operationResponse, $documentResponse): HttpResponseInterface {
                self::assertSame([
                    'headers' => [
                        'x-goog-api-key' => 'api-key',
                    ],
                ], $options);

                if (0 === $request++) {
                    self::assertSame('https://generativelanguage.googleapis.com/v1beta/fileSearchStores/store_123/operations/operation_123', $url);

                    return $operationResponse;
                }

                self::assertSame('https://generativelanguage.googleapis.com/v1beta/fileSearchStores/store_123/documents/document_123', $url);

                return $documentResponse;
            })
        ;

        $transport
            ->expects($this->exactly(3))
            ->method('decode')
            ->willReturnOnConsecutiveCalls(
                new ImportFileOperation('fileSearchStores/store_123/operations/operation_123'),
                new ImportFileOperation('fileSearchStores/store_123/operations/operation_123', true, new ImportFileResponse(documentName: 'fileSearchStores/store_123/documents/document_123')),
                new Document('fileSearchStores/store_123/documents/document_123', 'document.txt', new \DateTimeImmutable('2026-08-29T12:00:00Z'), new \DateTimeImmutable('2026-08-29T12:00:01Z'), DocumentState::Active, 123, 'text/plain'),
            )
        ;

        $file = $this->createProvider($transport)->attachFile('fileSearchStores/store_123', 'files/file_123', new Metadata());

        $this->assertSame('fileSearchStores/store_123/documents/document_123', $file->getId());
        $this->assertSame(FileState::Active, $file->getState());
        $this->assertSame(123, $file->getBytes());
    }

    public function testAttachingFileThrowsWhenOperationFails(): void
    {
        $response = $this->createStub(HttpResponseInterface::class);
        $transport = $this->createTransport();
        $transport->method('postRequest')->willReturn($response);
        $transport
            ->expects($this->once())
            ->method('decode')
            ->willReturn(new ImportFileOperation('fileSearchStores/store_123/operations/operation_123', true, error: new ImportFileStatus(13, 'Import failed')))
        ;
        $transport->expects($this->never())->method('getRequest');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageIs('The Gemini file import operation "fileSearchStores/store_123/operations/operation_123" failed: Import failed.');

        $this->createProvider($transport)->attachFile('fileSearchStores/store_123', 'files/file_123', new Metadata());
    }

    public function testAttachingFileRequiresDocumentName(): void
    {
        $response = $this->createStub(HttpResponseInterface::class);
        $transport = $this->createTransport();
        $transport->method('postRequest')->willReturn($response);
        $transport
            ->expects($this->once())
            ->method('decode')
            ->willReturn(new ImportFileOperation('fileSearchStores/store_123/operations/operation_123', true, new ImportFileResponse()))
        ;
        $transport->expects($this->never())->method('getRequest');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageIs('The Gemini file import operation "fileSearchStores/store_123/operations/operation_123" did not return a document name.');

        $this->createProvider($transport)->attachFile('fileSearchStores/store_123', 'files/file_123', new Metadata());
    }

    /**
     * @return Transport&MockObject
     */
    private function createTransport(): Transport
    {
        $transport = $this->createMock(Transport::class);
        $transport
            ->method('url')
            ->willReturnCallback(static fn (string ...$parts): string => implode('/', $parts))
        ;

        return $transport;
    }

    private function createProvider(Transport $transport): IndexProvider
    {
        $serializer = new Serializer();

        return new IndexProvider($transport, $serializer, 'api-key');
    }
}
