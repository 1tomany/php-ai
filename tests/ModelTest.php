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
    public function testConstructorRequiresNonEmptyId(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The model ID cannot be empty.');

        new Model(ModelVendor::Gemini, '');
    }

    public function testToStringReturnsFormattedName(): void
    {
        $modelName = 'openai:gpt-5.6-sol';

        $this->assertSame($modelName, Model::create($modelName)->__toString());
    }

    public function testCreateReturnsSelf(): void
    {
        $model = Model::create('openai:gpt-5.6-luna');

        $this->assertSame($model, Model::create($model));
    }

    public function testGettingNameReturnsFormattedName(): void
    {
        $modelName = 'gemini:gemini-3.7-flash';

        $this->assertSame($modelName, Model::create($modelName)->getName());
    }
}
