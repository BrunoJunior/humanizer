<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 26/02/19
 * Time: 21:25
 */

namespace Humanizer;

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
     * FrenchIntegerHumanizer constructor.
     * Init specialParts array
     */
    public function __construct()
    {
        $this->cardinals = [
            new FrenchCardinalNumber('cent', 3, 0, false),
            new FrenchCardinalNumber('mille', 9, 4, false, true),
            new FrenchCardinalNumber('million', 9, 7),
            new FrenchCardinalNumber('milliard', 9, 10)
        ];
    }

    /**
     * Humanize integer to french string
     * @param int $data
     * @return string
     * @throws WrongTypeException
     */
    public function humanize($data): string
    {
        if (!is_int($data)) {
            throw new WrongTypeException("Data must be an integer!");
        }
        if ($data === 0) {
            return 'zéro';
        }
        $start = '';
        if ($data < 0) {
            $start = 'moins ';
            $data = -$data;
        }
        return $start . $this->humanizeUnsigned($data);
    }

    /**
     * Humanize unsigned integer to french string
     * @param int $number
     * @return string
     * @throws WrongTypeException
     */
    private function humanizeUnsigned(int $number): string
    {
        if ($number < 100) {
            return $this->humanizeUnder100($number);
        }
        $humanizedFirsts = $this->humanizeFirstsDigit($number, $removed);
        $humanizedRest = $this->humanizeUnsigned((int) substr((string) $number, $removed));
        $humanizedRuled = $this->applyPluralRules($humanizedFirsts, $humanizedRest);
        return $humanizedRuled . ($humanizedRest ? ' ' : '') . $humanizedRest;
    }

    /**
     * French grammar for plural rules
     * @see French grammar rule : https://www.projet-voltaire.fr/regles-orthographe/cent-ou-cents/
     * @param string $start
     * @param string $end
     * @return string
     */
    private function applyPluralRules(string $start, string $end): string
    {
        if (substr($start, -5) === 'cents' && substr($end, 0, 4) !== 'mill' && !empty($end)) {
            return substr($start, 0, -1);
        }
        return $start;
    }

    /**
     * Humanize number between 0 and 100 (non inclusive)
     * @param int $number
     * @return string
     * @throws WrongTypeException
     */
    private function humanizeUnder100(int $number): string
    {
        if ($number < 17) {
            return static::UNDER_17[$number];
        }
        if ($number < 20) {
            return 'dix-' . $this->humanizeUnsigned($number - 10);
        }
        $decade = intdiv($number, 10);
        $unity = $number % 10;
        if ($decade === 7 || $decade === 9) {
            $unity += 10;
            $decade -= 1;
        }
        $humanized = static::DECADES[$decade];
        if ($decade < 8 && ($unity === 1 || $unity === 11)) {
            $humanized .= ' et ';
        } elseif ($unity > 0) {
            $humanized .= '-';
        } elseif ($decade === 8) {
            // @see French grammar rule : https://www.projet-voltaire.fr/regles-orthographe/ving-ou-vingts/
            $humanized .= 's';
        }
        return $humanized . $this->humanizeUnsigned($unity);
    }

    /**
     * Getting special part (cent, mille, million, milliard) depending on number of digits
     * @param int $number
     * @param int $removed
     * @return string
     * @throws WrongTypeException
     */
    private function humanizeFirstsDigit(int $number, &$removed): string
    {
        $strNum = (string) $number;
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
        $humanized = $this->humanizeUnsigned($firstDigit);
        if ($nbDigits > 2) {
            foreach ($this->cardinals as $cardinal) {
                $humanized = $cardinal->apply($nbDigits, $firstDigit, $humanized);
            }
        }
        return $humanized;
    }
}
