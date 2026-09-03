<?php

namespace OneToMany\AI\Tests;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\ModelVendor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function array_map;

#[Group('UnitTests')]
final class ModelVendorTest extends TestCase
{
    public function testCreateTrimsAndLowercasesVendor(): void
    {
        $vendor = ' GEMINI ';
        $this->assertNull(ModelVendor::tryFrom($vendor)); // @phpstan-ignore method.alreadyNarrowedType

        $modelVendor = ModelVendor::Gemini;
        $this->assertSame($modelVendor, ModelVendor::create($vendor));
    }

    #[DataProvider('providerModelVendor')]
    public function testCreateReturnsSelf(ModelVendor $vendor): void
    {
        $this->assertSame($vendor, ModelVendor::create($vendor));
    }

    #[DataProvider('providerModelVendor')]
    public function testCreateReturnsVendorFromValue(ModelVendor $vendor): void
    {
        $this->assertSame($vendor, ModelVendor::create($vendor->getValue()));
    }

    /**
     * @return non-empty-list<
     *   array{
     *     0: ModelVendor,
     *   },
     * >
     */
    public static function providerModelVendor(): array
    {
        return array_map(static fn (ModelVendor $v): array => [$v], ModelVendor::cases());
    }

    public function testCreateRequiresValidVendor(): void
    {
        $vendor = 'invalid_vendor';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The model vendor "'.$vendor.'" is not valid.');

        ModelVendor::create($vendor);
    }

    public function testFromModelRequiresValidFormat(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The model must use the "vendor:model" format.');

        ModelVendor::fromModel('gemini');
    }

    #[DataProvider('providerModelAndVendor')]
    public function testFromModel(
        string $model,
        ModelVendor $vendor,
    ): void {
        $this->assertSame($vendor, ModelVendor::fromModel($model));
    }

    /**
     * @return non-empty-list<
     *   array{
     *     0: non-empty-string,
     *     1: ModelVendor,
     *   },
     * >
     */
    public static function providerModelAndVendor(): array
    {
        $provider = [
            ['gemini:', ModelVendor::Gemini],
            ['meta:', ModelVendor::Meta],
            ['openai:', ModelVendor::OpenAI],
            ['gemini:gemini-flash', ModelVendor::Gemini],
            ['meta:muse-spark-1.3', ModelVendor::Meta],
            ['openai:gpt-5.6-sol', ModelVendor::OpenAI],
        ];

        return $provider;
    }

    public function testIsGemini(): void
    {
        $this->assertTrue(ModelVendor::Gemini->isGemini()); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testIsMeta(): void
    {
        $this->assertTrue(ModelVendor::Meta->isMeta()); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testIsOpenAI(): void
    {
        $this->assertTrue(ModelVendor::OpenAI->isOpenAI()); // @phpstan-ignore method.alreadyNarrowedType
    }
}
