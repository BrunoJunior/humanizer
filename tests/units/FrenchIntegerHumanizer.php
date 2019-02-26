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
class FrenchIntegerHumanizer extends \atoum
{
    public function testSimpleCase() {
        $this
            ->if($this->newTestedInstance)
            ->then
                ->string($this->testedInstance->humanize(0))
                    ->isIdenticalTo('zéro')
            ->and
                ->string($this->testedInstance->humanize(1))
                    ->isIdenticalTo('un')
            ->and
                ->string($this->testedInstance->humanize(2))
                    ->isIdenticalTo('deux')
            ->and
                ->string($this->testedInstance->humanize(12))
                    ->isIdenticalTo('douze')
            ->and
                ->string($this->testedInstance->humanize(18))
                    ->isIdenticalTo('dix-huit')
            ;
    }

    public function testDecades() {
        $this
            ->if($this->newTestedInstance)
            ->then
                ->string($this->testedInstance->humanize(25))
                    ->isIdenticalTo('vingt-cinq')
            ->and
                ->string($this->testedInstance->humanize(50))
                    ->isIdenticalTo('cinquante')
            ->and
                ->string($this->testedInstance->humanize(72))
                    ->isIdenticalTo('soixante-douze')
            ->and
                ->string($this->testedInstance->humanize(99))
                    ->isIdenticalTo('quatre-vingt-dix-neuf')
            ->and
                ->string($this->testedInstance->humanize(81))
                    ->isIdenticalTo('quatre-vingt-un')
            ->and
                ->string($this->testedInstance->humanize(91))
                    ->isIdenticalTo('quatre-vingt-onze')
            ->and
                ->string($this->testedInstance->humanize(31))
                    ->isIdenticalTo('trente et un')
        ;
    }

    public function testHundreds() {
        $this
            ->if($this->newTestedInstance)
            ->then
                ->string($this->testedInstance->humanize(101))
                    ->isIdenticalTo('cent un')
            ->and
                ->string($this->testedInstance->humanize(275))
                    ->isIdenticalTo('deux-cents soixante-quinze')
            ->and
                ->string($this->testedInstance->humanize(999))
                    ->isIdenticalTo('neuf-cents quatre-vingt-dix-neuf')
        ;
    }


    public function testSigned() {
        $this
            ->if($this->newTestedInstance)
            ->then
                ->string($this->testedInstance->humanize(-678))
                    ->isIdenticalTo('moins six-cents soixante-dix-huit')
        ;
    }

    public function testBigs() {
        $this
            ->if($this->newTestedInstance)
            ->then
                ->string($this->testedInstance->humanize(65535))
                    ->isIdenticalTo('soixante-cinq mille cinq-cents trente-cinq')
            ->and
                ->string($this->testedInstance->humanize(1000000))
                    ->isIdenticalTo('un million')
            ->and
                ->string($this->testedInstance->humanize(1024))
                    ->isIdenticalTo('mille vingt-quatre')
            ->and
                ->string($this->testedInstance->humanize(86435666087))
                    ->isIdenticalTo('quatre-vingt-six milliards quatre-cents trente-cinq millions six-cents soixante-six mille quatre-vingt-sept')
        ;
    }

    public function testNotInt() {
        $this
            ->if($this->newTestedInstance)
            ->then
                ->exception(function () {
                    $this->testedInstance->humanize('test');
                })
                ->isInstanceOf(\Humanizer\WrongTypeException::class)
        ;
    }
}