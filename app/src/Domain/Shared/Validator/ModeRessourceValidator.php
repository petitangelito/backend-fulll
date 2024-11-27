<?php

namespace App\Domain\Shared\Validator;

use App\Domain\Write\Ressource\RessourceDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ModeRessourceValidator extends ConstraintValidator
{
    /**
     * @param ModeRessource $constraint
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var ModeRessource $constraint */
        if (null === $value) {
            return;
        }

        if (!in_array($value, RessourceDto::MODES, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
