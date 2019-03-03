<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 02/03/19
 * Time: 23:59
 */

namespace Humanizer\tools;

use Humanizer\IHumanizer;

/**
 * Class FrIntBlocHumanizer
 * Humanize in french a positive integer which is under 1000
 * A bloc is composed with 3 digits max
 * @package Humanizer\tools
 */
class FrIntBlocHumanizer implements IHumanizer
{
    const UNDER_17 = [
        '', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze',
        'douze', 'treize', 'quatorze', 'quinze', 'seize'
    ];

    const DECADES = [
        2 => 'vingt', 3 => 'trente', 4 => 'quarante', 5 => 'cinquante', 6 => 'soixante', 8 => 'quatre-vingt'
    ];

    /**
     * @var int
     */
    private $number;

    /**
     * @var Stringer
     */
    private $output;

    /**
     * FrenchIntegerHumanizer constructor.
     * Init specialParts array
     * @param int $number
     */
    public function __construct(int $number)
    {
        // Max 999
        $this->number = $number % 1000;
        $this->output = new Stringer('');
    }

    /**
     * French humanization
     * @return string
     */
    public function humanize(): string
    {
        $this->humanizeUnder1000();
        return trim($this->output);
    }

    /**
     * Humanize number between 0 and 100 (non inclusive)
     */
    private function humanizeUnder100()
    {
        // Under 17, a french number can't be calculated, its just constant ...
        if ($this->number < 17) {
            $this->output->concat(static::UNDER_17[$this->number]);
            return;
        }
        if ($this->number < 20) {
            // For 17, 18 and 19, we can calculate the french number in this way :
            // Concatenate "dix-" and the constant for the number - 10
            $this->output->concat(static::UNDER_17[10])->addSeparator();
            $this->number -= 10;
        } else {
            $decade = intdiv($this->number, 10);
            $this->number = $this->number % 10;
            if ($decade === 7 || $decade === 9) {
                $this->number += 10;
                $decade -= 1;
            }
            $this->output->concat(static::DECADES[$decade]);
            if ($decade < 8 && ($this->number === 1 || $this->number === 11)) {
                $this->output->concat(' et ');
            } elseif ($this->number > 0) {
                $this->output->addSeparator();
            } elseif ($decade === 8) {
                // @see French grammar rule : https://www.projet-voltaire.fr/regles-orthographe/ving-ou-vingts/
                $this->output->pluralize();
            }
        }
        $this->humanizeUnder100();
    }

    /**
     * Humanize number between 0 and 1000 (non inclusive)
     */
    private function humanizeUnder1000()
    {
        if ($this->number > 99) {
            $hundreds = intdiv($this->number, 100);
            $rest = $this->number % 100;
            $this->number = $hundreds;
            if ($hundreds > 1) {
                $this->humanizeUnder100();
                $this->output->addSpace();
            }
            $this->output->concat('cent');
            $this->number = $rest;
            if ($this->number === 0 && $hundreds > 1) {
                $this->output->pluralize();
            }
            if ($this->number > 0) {
                $this->output->addSpace();
            }
        }
        $this->humanizeUnder100();
    }
}