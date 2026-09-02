<?php

namespace OneToMany\AI\Tests\Validator;

use OneToMany\AI\ModelVendor;
use OneToMany\AI\Validator\ModelVendorName;
use OneToMany\AI\Validator\ModelVendorNameValidator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

#[Group('ValidatorTests')]
final class ModelVendorNameValidatorTest extends TestCase
{
    public function testValidateRequiresModelVendorNameConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessageIs('Expected argument of type "'.ModelVendorName::class.'", "'.Assert\Blank::class.'" given');

        new ModelVendorNameValidator()->validate('gemini', new Assert\Blank());
    }

    public function testValidateIgnoresNullValues(): void
    {
        $this->expectNotToPerformAssertions();

        new ModelVendorNameValidator()->validate(null, new ModelVendorName());
    }

    public function testValidateIgnoresValuesOfTypeModelVendor(): void
    {
        $this->expectNotToPerformAssertions();

        new ModelVendorNameValidator()->validate(ModelVendor::Gemini, new ModelVendorName());
    }

    public function testValidateRequiresValueToBeString(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessageIs('Expected argument of type "string", "array" given');

        new ModelVendorNameValidator()->validate(['openai'], new ModelVendorName());
    }

    public function testValidatingValidModel(): void
    {
        $this->expectNotToPerformAssertions();

        new ModelVendorNameValidator()->validate('openai', new ModelVendorName());
    }
}
