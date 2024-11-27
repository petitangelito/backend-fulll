<?php

namespace App\Domain\Shared\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LatitudeValidator extends ConstraintValidator
{
    /**
     * @param Latitude $constraint
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var Latitude $constraint */
        if ($value > 90 || $value < -90) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
