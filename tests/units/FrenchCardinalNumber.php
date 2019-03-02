<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 26/02/19
 * Time: 21:25
 */

namespace Humanizer\tests\units;

/**
 * Class FrenchIntegerHumanizer
 * Humanize integer to french
 * @package Humanizer
 */
class FrenchCardinalNumber extends \atoum
{
    public function testValidity() {
        $this
            ->if($this->newTestedInstance('test', 3, 0))
            ->then
                ->boolean($this->testedInstance->isValid(1))
                    ->isFalse
            ->and
                ->boolean($this->testedInstance->isValid(3))
                    ->isTrue
            ;
    }

    public function testWithOnes() {
        $this
            ->if($this->newTestedInstance('test', 3, 0))
            ->then
                ->string($this->testedInstance->apply(1, 'un'))
                    ->isIdenticalTo('un test')
        ;
    }

    public function testWithoutOnes() {
        $this
            ->if($this->newTestedInstance('test', 3, 0, false))
            ->then
                ->string($this->testedInstance->apply(1, 'un'))
                    ->isIdenticalTo('test')
        ;
    }

    public function testInvariablePlural() {
        $this
            ->if($this->newTestedInstance('test', 3, 0, true, true))
            ->then
                ->string($this->testedInstance->apply(2, 'deux'))
                    ->isIdenticalTo('deux test')
        ;
    }

    public function testVariablePlural() {
        $this
            ->if($this->newTestedInstance('test', 3, 0, true, false))
            ->then
                ->string($this->testedInstance->apply(2, 'deux'))
                    ->isIdenticalTo('deux tests')
        ;
    }
}
