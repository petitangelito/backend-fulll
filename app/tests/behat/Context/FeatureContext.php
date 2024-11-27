<?php

declare(strict_types=1);

namespace Tests\Behat\Context;

use App\App\Calculator;
use Behat\Behat\Context\Context;

readonly class FeatureContext implements Context
{
    private int $return;

    /**
     * @When I multiply :a by :b into :var
     */
    public function iMultiply(int $a, int $b, string $return): void
    {
        $calculator = new Calculator();
        $this->$return = $calculator->multiply($a, $b);
    }

    /**
     * @Then :var should be equal to :value
     */
    public function aShouldBeEqualTo(string $return, int $value): void
    {
        if ($this->$return !== $value) {
            throw new \RuntimeException(sprintf('%s is expected to be equal to %s, got %s', $return, $value, $this->$return));
        }
    }

    /**
     * @Then :var should not be equal to :value
     */
    public function aShouldNotBeEqualTo(string $return, int $value): void
    {
        if ($this->$return == $value) {
            throw new \RuntimeException(sprintf('%s is expected not to be equal to %s, got %s', $return, $value, $this->$return));
        }
    }
}
