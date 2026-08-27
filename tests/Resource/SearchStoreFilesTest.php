<?php

namespace OneToMany\AI\Tests\Resource;

use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;
use OneToMany\AI\Resource\SearchStoreFiles;
use OneToMany\AI\Tests\Fixtures\SearchStoreProvider;
use OneToMany\AI\Vendor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
final class SearchStoreFilesTest extends TestCase
{
    private SearchStoreProvider $provider;
    private SearchStoreFiles $files;

    protected function setUp(): void
    {
        $this->provider = new SearchStoreProvider();
        $this->files = new SearchStoreFiles([$this->provider]);
    }

    public function testAttachDelegatesToProvider(): void
    {
        $searchStoreFile = new SearchStoreFile(
            'fileSearchStores/docs/documents/guide',
            'fileSearchStores/docs',
            'files/guide',
            metadata: ['section' => 'reference'],
        );
        $this->provider->searchStoreFile = $searchStoreFile;

        $this->assertSame(
            $searchStoreFile,
            $this->files->attach(
                Vendor::OpenAI,
                ' fileSearchStores/docs ',
                ' files/guide ',
                ['section' => 'reference'],
                true,
            ),
        );
        $this->assertSame(
            ['fileSearchStores/docs', 'files/guide', ['section' => 'reference'], true],
            $this->provider->attachFileArguments,
        );
    }

    public function testReadDelegatesToProvider(): void
    {
        $searchStoreFile = new SearchStoreFile(
            'fileSearchStores/docs/documents/guide',
            'fileSearchStores/docs',
            'files/guide',
        );
        $this->provider->searchStoreFile = $searchStoreFile;

        $this->assertSame(
            $searchStoreFile,
            $this->files->read(
                Vendor::OpenAI,
                ' fileSearchStores/docs ',
                ' fileSearchStores/docs/documents/guide ',
            ),
        );
        $this->assertSame(
            ['fileSearchStores/docs', 'fileSearchStores/docs/documents/guide'],
            $this->provider->readFileArguments,
        );
    }

    public function testDeleteDelegatesToProvider(): void
    {
        $this->files->delete(
            Vendor::OpenAI,
            ' fileSearchStores/docs ',
            ' fileSearchStores/docs/documents/guide ',
        );

        $this->assertSame(
            ['fileSearchStores/docs', 'fileSearchStores/docs/documents/guide'],
            $this->provider->deleteFileArguments,
        );
    }

    /**
     * @param array<array-key, mixed> $metadata
     */
    #[DataProvider('providerInvalidMetadata')]
    public function testAttachValidatesMetadata(array $metadata, string $message): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs($message);

        $this->files->attach(Vendor::OpenAI, 'fileSearchStores/docs', 'files/guide', $metadata);
    }

    /**
     * @return list<array{array<array-key, mixed>, string}>
     */
    public static function providerInvalidMetadata(): array
    {
        return [
            [[0 => 'reference'], 'Search store file metadata keys must be non-empty strings.'],
            [['' => 'reference'], 'Search store file metadata keys must be non-empty strings.'],
            [['section' => []], 'The search store file metadata value for key "section" must be a scalar type.'],
        ];
    }

    #[DataProvider('providerEmptyValue')]
    public function testAttachRequiresASearchStoreId(?string $searchStoreId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('The search store ID cannot be empty.');

        $this->files->attach(Vendor::OpenAI, $searchStoreId, 'files/guide');
    }

    #[DataProvider('providerEmptyValue')]
    public function testAttachRequiresAFileId(?string $fileId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('The file ID cannot be empty.');

        $this->files->attach(Vendor::OpenAI, 'fileSearchStores/docs', $fileId);
    }

    #[DataProvider('providerEmptyValue')]
    public function testReadRequiresASearchStoreFileId(?string $searchStoreFileId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('The search store file ID cannot be empty.');

        $this->files->read(Vendor::OpenAI, 'fileSearchStores/docs', $searchStoreFileId);
    }

    #[DataProvider('providerEmptyValue')]
    public function testDeleteRequiresASearchStoreFileId(?string $searchStoreFileId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('The search store file ID cannot be empty.');

        $this->files->delete(Vendor::OpenAI, 'fileSearchStores/docs', $searchStoreFileId);
    }

    /**
     * @return list<array{?string}>
     */
    public static function providerEmptyValue(): array
    {
        return [[null], [''], ['  ']];
    }
}
