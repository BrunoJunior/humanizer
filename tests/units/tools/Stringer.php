<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 03/03/19
 * Time: 18:57
 */

namespace Humanizer\tools\tests\units;

class Stringer extends \atoum
{
    public function testConcat()
    {
        $this
            ->if($this->newTestedInstance(''))
            ->then
                ->object($this->testedInstance->concat('a', false))
                    ->toString
                        ->isIdenticalTo('')
            ->and
                ->object($this->testedInstance->concat('a'))
                    ->toString
                        ->isIdenticalTo('a');
    }

    public function testConcatPrefix()
    {
        $this
            ->if($this->newTestedInstance('a'))
            ->then
                ->object($this->testedInstance->concatWithPrefix(''))
                    ->toString
                        ->isIdenticalTo('a')
            ->and
                ->object($this->testedInstance->concatWithPrefix('b'))
                    ->toString
                        ->isIdenticalTo('a b')
            ->and
                ->object($this->testedInstance->concatWithPrefix('c', '-'))
                    ->toString
                        ->isIdenticalTo('a b-c');
    }

    public function testAddSeparator()
    {
        $this
            ->if($this->newTestedInstance(''))
            ->then
                ->object($this->testedInstance->addSeparator(false))
                    ->toString
                        ->isIdenticalTo('')
            ->and
                ->object($this->testedInstance->addSeparator())
                    ->toString
                        ->isIdenticalTo('-');
    }

    public function testAddSpace()
    {
        $this
            ->if($this->newTestedInstance(''))
            ->then
                ->object($this->testedInstance->addSpace(false))
                    ->toString
                        ->isIdenticalTo('')
            ->and
                ->object($this->testedInstance->addSpace())
                    ->toString
                        ->isIdenticalTo(' ');
    }

    public function testPluralize()
    {
        $this
            ->if($this->newTestedInstance(''))
            ->then
                ->object($this->testedInstance->pluralize(false))
                    ->toString
                        ->isIdenticalTo('')
            ->and
                ->object($this->testedInstance->concat('test')->pluralize())
                    ->toString
                        ->isIdenticalTo('tests');
    }

    public function testPrefix()
    {
        $this
            ->if($this->newTestedInstance(''))
            ->then
                ->object($this->testedInstance->prefix('a', false))
                    ->toString
                        ->isIdenticalTo('')
            ->and
                ->object($this->testedInstance->prefix('a')->prefix('b'))
                    ->toString
                        ->isIdenticalTo('ba')
            ->and
                ->object($this->testedInstance->prefix(''))
                    ->toString
                        ->isIdenticalTo('ba');
    }
}