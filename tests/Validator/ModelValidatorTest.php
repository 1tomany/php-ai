<?php

namespace OneToMany\AI\Tests\Validator;

use OneToMany\AI\Validator\ModelName;
use OneToMany\AI\Validator\ModelNameValidator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

#[Group('ValidatorTests')]
final class ModelValidatorTest extends TestCase
{
    public function testValidateRequiresModelConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessageIs('Expected argument of type "'.ModelName::class.'", "'.Assert\Blank::class.'" given');

        new ModelNameValidator()->validate('mock:model', new Assert\Blank());
    }

    public function testValidateIgnoresNullValues(): void
    {
        $this->expectNotToPerformAssertions();

        new ModelNameValidator()->validate(null, new ModelName());
    }

    public function testValidateRequiresValueToBeString(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessageIs('Expected argument of type "string", "array" given');

        new ModelNameValidator()->validate(['mock:model'], new ModelName());
    }

    public function testValidatingValidModel(): void
    {
        $this->expectNotToPerformAssertions();

        new ModelNameValidator()->validate('openai:gpt-5.6-sol', new ModelName());
    }
}
