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

    private static $specialParts;

    /**
     * FrenchIntegerHumanizer constructor.
     * Init specialParts array
     */
    public function __construct()
    {
        if (!isset(static::$specialParts)) {
            static::$specialParts = [
                'cent' => [
                    'invariable' => false,
                    'printOne' => false,
                    'isValid' => function ($nbDigits) {return $nbDigits % 3 === 0;}
                    ],
                'mille' => [
                    'invariable' => true,
                    'printOne' => false,
                    'isValid' => function ($nbDigits) {return ($nbDigits - 4) % 9 === 0;}
                ],
                'million' => [
                    'invariable' => false,
                    'printOne' => true,
                    'isValid' => function ($nbDigits) {return ($nbDigits - 7) % 9 === 0;}
                ],
                'milliard' => [
                    'invariable' => false,
                    'printOne' => true,
                    'isValid' => function ($nbDigits) {return ($nbDigits - 10) % 9 === 0;}
                ]
            ];
        }
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
        //@see French grammar rule : https://www.projet-voltaire.fr/regles-orthographe/cent-ou-cents/
        if (substr($humanizedFirsts, -5) === 'cents' && substr($humanizedRest, 0, 4) !== 'mill' && !empty($humanizedRest)) {
            $humanizedFirsts = substr($humanizedFirsts, 0, -1);
        }
        return $humanizedFirsts . (empty($humanizedRest) ? '' : ' ') . $humanizedRest;
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
            foreach (static::$specialParts as $key => $infos) {
                if (!$infos['isValid']($nbDigits)) {
                    continue;
                }
                $specialPart = $key;
                if ($firstDigit === 1 && !$infos['printOne']) {
                    $humanized = '';
                } elseif ($firstDigit > 1 && !$infos['invariable']) {
                    $specialPart .= 's';
                }
                if (!empty($humanized)) {
                    $humanized .= ' ';
                }
                $humanized .= $specialPart;
            }
        }
        return $humanized;
    }
}
