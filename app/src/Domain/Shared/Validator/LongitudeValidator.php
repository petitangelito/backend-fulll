<?php

namespace App\Domain\Shared\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LongitudeValidator extends ConstraintValidator
{
    /**
     * @param Longitude $constraint
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var Longitude $constraint */
        if ($value > 180 || $value < -180) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
