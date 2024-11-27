<?php

namespace App\Tests\Unit\Domain\Shared\Validator;

use App\Domain\Shared\Validator\ModeRessource;
use App\Domain\Shared\Validator\ModeRessourceValidator;
use App\Domain\Write\Ressource\RessourceDto;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ModeRessourceValidatorTest extends TestCase
{
    public function getValidator($expectedViolation = false): ModeRessourceValidator
    {
        $validator = new ModeRessourceValidator();
        $context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();
        if ($expectedViolation) {
            $context->expects($this->once())->method('buildViolation');
        } else {
            $context->expects($this->never())->method('buildViolation');
        }

        $validator->initialize($context);

        return $validator;
    }

    public function testModeRessourceValidatorPass(): void
    {
        $constraint = new ModeRessource();
        $this->getValidator()->validate(Factory::create()->randomElement(RessourceDto::MODES), $constraint);
    }

    public function testModeRessourceValidatorRaiseError(): void
    {
        $constraint = new ModeRessource();
        $this->getValidator(true)->validate(Factory::create()->text(20), $constraint);
    }
}
