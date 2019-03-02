<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 26/02/19
 * Time: 21:25
 */

namespace Humanizer;

use Humanizer\tools\StringConcat;

/**
 * Class FrenchIntegerHumanizer
 * Humanize integer to french
 * @package Humanizer
 */
class FrenchIntegerHumanizer implements IHumanizer
{
    const UNDER_17 = [
        '', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze',
        'douze', 'treize', 'quatorze', 'quinze', 'seize'
        ];

    const DECADES = [
        2 => 'vingt', 3 => 'trente', 4 => 'quarante', 5 => 'cinquante', 6 => 'soixante', 8 => 'quatre-vingt'
    ];

    /**
     * @var FrenchCardinalNumber[]
     */
    private $cardinals;

    /**
     * @var int
     */
    private $number;

    /**
     * @var StringConcat
     */
    private $output;

    /**
     * FrenchIntegerHumanizer constructor.
     * Init specialParts array
     * @param int $number
     */
    public function __construct(int $number)
    {
        $this->number = $number;
        $this->output = new StringConcat('');
        $this->cardinals = [
            new FrenchCardinalNumber('mille', 9, 4, false, true),
            new FrenchCardinalNumber('million', 9, 7),
            new FrenchCardinalNumber('milliard', 9, 10)
        ];
    }

    /**
     * Humanize integer to french string
     * @return string
     */
    public function humanize(): string
    {
        if ($this->number === 0) {
            return 'zéro';
        }
        if ($this->number < 0) {
            $this->output->concat('moins')->addSpace();
            $this->number = -$this->number;
        }
        $this->humanizeUnsigned();
        return trim($this->output);
    }

    /**
     * Humanize unsigned integer to french string
     * @return string
     */
    private function humanizeUnsigned()
    {
        if ($this->number < 1000) {
            $this->humanizeUnder1000();
            return;
        }
        $this->humanizeFirstsDigit();
    }

    /**
     * Humanize number between 0 and 100 (non inclusive)
     * @return string
     */
    private function humanizeUnder100()
    {
        if ($this->number < 17) {
            $this->output->concat(static::UNDER_17[$this->number]);
            return;
        }
        if ($this->number < 20) {
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
        $this->humanizeUnsigned();
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
                $this->humanizeUnsigned();
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

    /**
     * Getting special part (cent, mille, million, milliard) depending on number of digits
     */
    private function humanizeFirstsDigit()
    {
        $strNum = (string) $this->number;
        $nbDigits = strlen($strNum);
        $removed = 1;
        if ($nbDigits > 2 && ($nbDigits - 2) % 3 === 0) {
            $removed = 2;
        } elseif ($nbDigits >= 6 && ($nbDigits - 6) % 3 === 0) {
            $removed = 3;
        } elseif ($nbDigits >= 13 && ($nbDigits - 13) % 3 === 0) {
            $removed = 4;
        }
        $nbDigits = $nbDigits - $removed + 1;
        $firstDigit = (int) substr($strNum, 0, $removed);
        $this->number = (int) substr($strNum, $removed);
        $humanized = (new FrenchIntegerHumanizer($firstDigit))->humanize();
        if ($nbDigits > 2) {
            foreach ($this->cardinals as $cardinal) {
                if ($cardinal->isValid($nbDigits)) {
                    $humanized = $cardinal->apply($firstDigit, $humanized);
                    break;
                }
            }
        }
        $this->output->concat($humanized)->addSpace();
        $this->humanizeUnsigned();
    }
}
