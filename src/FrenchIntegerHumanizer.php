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
                    'separator' => '-',
                    'printOne' => false,
                    'isValid' => function ($nbDigits) {return $nbDigits % 3 === 0;}
                    ],
                'mille' => [
                    'invariable' => true,
                    'separator' => '-',
                    'printOne' => false,
                    'isValid' => function ($nbDigits) {return ($nbDigits - 4) % 9 === 0;}
                ],
                'million' => [
                    'invariable' => false,
                    'separator' => ' ',
                    'printOne' => true,
                    'isValid' => function ($nbDigits) {return ($nbDigits - 7) % 9 === 0;}
                ],
                'milliard' => [
                    'invariable' => false,
                    'separator' => ' ',
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
        $firstDigitHumanized = $this->humanizeFirstDigit($number);
        $numberWithoutFirstDigit = intval(substr(strval($number), 1), 10);
        return $firstDigitHumanized . ' ' . $this->humanizeUnsigned($numberWithoutFirstDigit);
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
     * @return string
     * @throws WrongTypeException
     */
    private function humanizeFirstDigit(int $number): string
    {
        $strNum = strval($number);
        $nbDigits = strlen($strNum);
        $firstDigit = intval($strNum[0], 10);
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
                    $humanized .= $infos['separator'];
                }
                $humanized .= $specialPart;
            }
        }
        return $humanized;
    }
}