<?php

namespace OneToMany\AI\Tests\Resource;

use OneToMany\AI\Contract\Resource\SearchStoreFilesInterface;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Resource\SearchStoreFiles;
use OneToMany\AI\Resource\SearchStores;
use OneToMany\AI\Tests\Fixtures\SearchStoreProvider;
use OneToMany\AI\Vendor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
final class SearchStoresTest extends TestCase
{
    private SearchStoreProvider $provider;
    private SearchStoreFilesInterface $files;
    private SearchStores $searchStores;

    protected function setUp(): void
    {
        $this->provider = new SearchStoreProvider();
        $this->files = new SearchStoreFiles([$this->provider]);
        $this->searchStores = new SearchStores([$this->provider], $this->files);
    }

    public function testExposesFilesFacade(): void
    {
        $this->assertSame($this->files, $this->searchStores->files);
    }

    public function testCreateDelegatesToProvider(): void
    {
        $searchStore = new SearchStore('vs_123', 'Documentation', 'Current documentation');
        $this->provider->searchStore = $searchStore;

        $this->assertSame(
            $searchStore,
            $this->searchStores->create(Vendor::OpenAI, ' Documentation ', ' Current documentation '),
        );
        $this->assertSame(['Documentation', 'Current documentation'], $this->provider->createArguments);
    }

    public function testCreateNormalizesAnEmptyDescription(): void
    {
        $searchStore = new SearchStore('vs_123', 'Documentation');
        $this->provider->searchStore = $searchStore;

        $this->assertSame($searchStore, $this->searchStores->create('openai', 'Documentation', '  '));
        $this->assertSame(['Documentation', null], $this->provider->createArguments);
    }

    #[DataProvider('providerEmptyValue')]
    public function testCreateRequiresAName(?string $name): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('The search store name cannot be empty.');

        $this->searchStores->create(Vendor::OpenAI, $name);
    }

    public function testReadDelegatesToProvider(): void
    {
        $searchStore = new SearchStore('vs_123', 'Documentation');
        $this->provider->searchStore = $searchStore;

        $this->assertSame($searchStore, $this->searchStores->read(Vendor::OpenAI, ' vs_123 '));
        $this->assertSame('vs_123', $this->provider->readId);
    }

    public function testDeleteDelegatesToProvider(): void
    {
        $this->searchStores->delete(Vendor::OpenAI, ' vs_123 ');

        $this->assertSame('vs_123', $this->provider->deleteId);
    }

    #[DataProvider('providerEmptyValue')]
    public function testReadRequiresAnId(?string $searchStoreId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('The search store ID cannot be empty.');

        $this->searchStores->read(Vendor::OpenAI, $searchStoreId);
    }

    #[DataProvider('providerEmptyValue')]
    public function testDeleteRequiresAnId(?string $searchStoreId): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('The search store ID cannot be empty.');

        $this->searchStores->delete(Vendor::OpenAI, $searchStoreId);
    }

    /**
     * @return list<array{?string}>
     */
    public static function providerEmptyValue(): array
    {
        return [[null], [''], ['  ']];
    }
}
