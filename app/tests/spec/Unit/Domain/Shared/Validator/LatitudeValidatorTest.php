<?php

namespace App\Tests\Unit\Domain\Shared\Validator;

use App\Domain\Shared\Validator\Latitude;
use App\Domain\Shared\Validator\LatitudeValidator;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LatitudeValidatorTest extends TestCase
{
    public function getValidator($expectedViolation = false): LatitudeValidator
    {
        $validator = new LatitudeValidator();
        $context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();
        if ($expectedViolation) {
            $context->expects($this->once())->method('buildViolation');
        } else {
            $context->expects($this->never())->method('buildViolation');
        }

        $validator->initialize($context);

        return $validator;
    }

    public function testLatitudeValidatorPass(): void
    {
        $constraint = new Latitude();
        $this->getValidator()->validate(Factory::create()->latitude(), $constraint);
    }

    public function testLatitudeValidatorRaiseError(): void
    {
        $constraint = new Latitude();
        $this->getValidator(true)->validate(Factory::create()->latitude(-92, -91), $constraint);
    }
}
