<?php

namespace OneToMany\AI\Validator;

use OneToMany\AI\Contract\Exception\ExceptionInterface;
use OneToMany\AI\ModelVendor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use function is_string;

final class ModelVendorNameValidator extends ConstraintValidator
{
    /**
     * @see Symfony\Component\Validator\ConstraintValidator
     *
     * @throws UnexpectedTypeException when the constraint is not a {@see ModelVendorName} object
     * @throws UnexpectedValueException when the value is not null and not a string
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ModelVendorName) {
            throw new UnexpectedTypeException($constraint, ModelVendorName::class);
        }

        if (null === $value) {
            return;
        }

        if ($value instanceof ModelVendor) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        try {
            ModelVendor::create($value);
        } catch (ExceptionInterface $e) {
            $this->context->buildViolation($e->getMessage())->addViolation();
        }
    }
}
