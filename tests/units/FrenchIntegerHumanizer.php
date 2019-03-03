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
    public function testZero() {
        $this->isWellHumanized(0, 'zéro');
    }

    public function testPlurals() {
        $this
            ->isWellHumanized(80000, 'quatre-vingts mille')
            ->isWellHumanized(80000000, 'quatre-vingts millions')
        ;
    }

    public function testSigned() {
        $this
            ->isWellHumanized(-678, 'moins six cent soixante-dix-huit')
        ;
    }

    public function testTensPowers() {
        $this
            ->isWellHumanized(1000, 'mille')
            ->isWellHumanized(10000, 'dix mille')
            ->isWellHumanized(100000, 'cent mille')
            ->isWellHumanized(1000000, 'un million')
            ->isWellHumanized(10000000, 'dix millions')
            ->isWellHumanized(100000000, 'cent millions')
            ->isWellHumanized(1000000000, 'un milliard')
            ->isWellHumanized(10000000000, 'dix milliards')
            ->isWellHumanized(100000000000, 'cent milliards')
            ->isWellHumanized(1000000000000, 'mille milliards')
            ;
    }

    public function testBigOnes() {
        $this
            ->isWellHumanized(1024, 'mille vingt-quatre')
            ->isWellHumanized(65535, 'soixante-cinq mille cinq cent trente-cinq')
            ->isWellHumanized(200000000, 'deux cents millions')
            ->isWellHumanized(86435666087, 'quatre-vingt-six milliards quatre cent trente-cinq millions six cent soixante-six mille quatre-vingt-sept')
        ;
    }
}
