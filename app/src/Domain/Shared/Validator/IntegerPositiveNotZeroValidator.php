<?php

namespace App\Domain\Shared\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IntegerPositiveNotZeroValidator extends ConstraintValidator
{
    /**
     * @param IntegerPositiveNotZero $constraint
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var IntegerPositiveNotZero $constraint */
        if (null === $value) {
            return;
        }

        if (!is_int($value) || $value <= 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
