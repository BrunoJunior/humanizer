<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 03/03/19
 * Time: 18:57
 */

namespace Humanizer\tools\tests\units;

use Humanizer\Tests\TestHumanizer;

class FrIntBlocHumanizer extends TestHumanizer
{
    public function testZero() {
        $this->isWellHumanized(0, '');
    }

    public function testUn() {
        $this->isWellHumanized(1, 'un');
    }

    public function testDeux() {
        $this->isWellHumanized(2, 'deux');
    }

    public function testDouze() {
        $this->isWellHumanized(12, 'douze');
    }

    public function testDixHuit() {
        $this->isWellHumanized(18, 'dix-huit');
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
            ->isWellHumanized(100, 'cent')
        ;
    }

    public function testPlurals() {
        $this
            ->isWellHumanized(80, 'quatre-vingts')
            ->isWellHumanized(200, 'deux cents');
    }
}