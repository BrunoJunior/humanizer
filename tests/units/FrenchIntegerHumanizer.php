<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 26/02/19
 * Time: 21:25
 */

namespace Humanizer\tests\units;

use Humanizer\Tests\TestHumanizer;

/**
 * Class FrenchIntegerHumanizer
 * Humanize integer to french
 * @package Humanizer
 */
class FrenchIntegerHumanizer extends TestHumanizer
{
    public function testSimpleCase() {
        $this
            ->isWellHumanized(0, 'zéro')
            ->isWellHumanized(1, 'un')
            ->isWellHumanized(2, 'deux')
            ->isWellHumanized(12, 'douze')
            ->isWellHumanized(18, 'dix-huit')
        ;
    }

    public function testDecades() {
        $this
            ->isWellHumanized(25, 'vingt-cinq')
            ->isWellHumanized(50, 'cinquante')
            ->isWellHumanized(72, 'soixante-douze')
            ->isWellHumanized(99, 'quatre-vingt-dix-neuf')
            ->isWellHumanized(81, 'quatre-vingt-un')
            ->isWellHumanized(91, 'quatre-vingt-onze')
            ->isWellHumanized(31, 'trente et un')
        ;
    }

    public function testHundreds() {
        $this
            ->isWellHumanized(101, 'cent un')
            ->isWellHumanized(275, 'deux cent soixante-quinze')
            ->isWellHumanized(999, 'neuf cent quatre-vingt-dix-neuf')
            ->isWellHumanized(200, 'deux cents')
        ;
    }

    public function testPlurals() {
        $this
            ->isWellHumanized(80, 'quatre-vingts')
            ->isWellHumanized(80000, 'quatre-vingts mille')
            ->isWellHumanized(80000000, 'quatre-vingts millions')
        ;
    }


    public function testSigned() {
        $this
            ->isWellHumanized(-678, 'moins six cent soixante-dix-huit')
        ;
    }

    public function testBigs() {
        $this
            ->isWellHumanized(65535, 'soixante-cinq mille cinq cent trente-cinq')
            ->isWellHumanized(1000000, 'un million')
            ->isWellHumanized(1000, 'mille')
            ->isWellHumanized(200000000, 'deux cents millions')
            ->isWellHumanized(10000, 'dix mille')
            ->isWellHumanized(100000, 'cent mille')
            ->isWellHumanized(1000000000, 'un milliard')
            ->isWellHumanized(100000000000, 'cent milliards')
            ->isWellHumanized(1000000000000, 'mille milliards')
            ->isWellHumanized(1024, 'mille vingt-quatre')
            ->isWellHumanized(86435666087, 'quatre-vingt-six milliards quatre cent trente-cinq millions six cent soixante-six mille quatre-vingt-sept')
        ;
    }
}
