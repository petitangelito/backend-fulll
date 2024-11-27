<?php

namespace App\Tests\Unit\Domain\Shared\Validator;

use App\Domain\Shared\Validator\Longitude;
use App\Domain\Shared\Validator\LongitudeValidator;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LongitudeValidatorTest extends TestCase
{
    public function getValidator($expectedViolation = false): LongitudeValidator
    {
        $validator = new LongitudeValidator();

        $context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();
        if ($expectedViolation) {
            $context->expects($this->once())->method('buildViolation');
        } else {
            $context->expects($this->never())->method('buildViolation');
        }

        $validator->initialize($context);

        return $validator;
    }

    public function testLongitudeValidatorPass(): void
    {
        $faker = Factory::create();
        $constraint = new Longitude();
        $this->getValidator()->validate($faker->longitude(), $constraint);
    }

    public function testLongitudeValidatorRaiseError(): void
    {
        $faker = Factory::create();
        $constraint = new Longitude();
        $this->getValidator(true)->validate($faker->longitude(-182, -181), $constraint);
    }
}
