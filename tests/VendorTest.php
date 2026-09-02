<?php

namespace OneToMany\AI\Tests;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\ModelVendor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function array_map;

#[Group('UnitTests')]
final class VendorTest extends TestCase
{
    #[DataProvider('providerVendor')]
    public function testCreateReturnsSelf(ModelVendor $vendor): void
    {
        $this->assertSame($vendor, ModelVendor::create($vendor));
    }

    /**
     * @return non-empty-list<array{Vendor}>
     */
    public static function providerVendor(): array
    {
        return array_map(static fn (ModelVendor $v): array => [$v], ModelVendor::cases());
    }

    public function testCreateRequiresValidVendor(): void
    {
        $vendor = 'invalid_vendor';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The vendor "'.$vendor.'" is not valid.');

        ModelVendor::create($vendor);
    }

    public function testFromModelRequiresValidFormat(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The model must use the "vendor:model" format.');

        ModelVendor::fromModel('gemini');
    }

    #[DataProvider('providerModelAndVendor')]
    public function testFromModel(string $model, ModelVendor $vendor): void
    {
        $this->assertSame($vendor, ModelVendor::fromModel($model));
    }

    /**
     * @return non-empty-list<array{non-empty-string, Vendor}>
     */
    public static function providerModelAndVendor(): array
    {
        $provider = [
            ['gemini:', ModelVendor::Gemini],
            ['openai:', ModelVendor::OpenAI],
            ['gemini:gemini-flash', ModelVendor::Gemini],
            ['openai:gpt-5.6-sol', ModelVendor::OpenAI],
        ];

        return $provider;
    }
}
