<?php

namespace OneToMany\AI\Tests;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Model;
use OneToMany\AI\ModelVendor;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
final class ModelTest extends TestCase
{
    public function testConstructorRequiresNonEmptyName(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The model name cannot be empty.');

        new Model(ModelVendor::Gemini, '');
    }

    public function testToStringReturnsFormattedName(): void
    {
        $this->assertSame('openai:gpt-5.6-sol', new Model(ModelVendor::OpenAI, 'gpt-5.6-sol')->__toString());
    }

    public function testCreateReturnsSelf(): void
    {
        $model = new Model(ModelVendor::OpenAI, 'gpt-5.6-luna');

        $this->assertSame($model, Model::create($model));
    }
}
