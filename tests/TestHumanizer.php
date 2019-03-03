<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 02/03/19
 * Time: 00:06
 */

namespace Humanizer\Tests;


class TestHumanizer extends \atoum
{
    /**
     * Check if value is well humanized
     * @param $value
     * @param string $expected
     * @return TestHumanizer
     */
    protected function isWellHumanized($value, string $expected): self
    {
        $this
            ->if($this->newTestedInstance($value))
            ->then
                ->string($this->testedInstance->humanize())
                    ->isIdenticalTo($expected);
        return $this;
    }
}