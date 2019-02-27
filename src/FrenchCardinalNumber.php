<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 27/02/19
 * Time: 22:59
 */

namespace Humanizer;

/**
 * Class FrenchCardinalNumber
 * @package Humanizer
 */
class FrenchCardinalNumber
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var bool
     */
    private $invariable;
    /**
     * @var bool
     */
    private $printOnes;
    /**
     * @var int
     */
    private $modulo;
    /**
     * @var int
     */
    private $offset;

    /**
     * FrenchCardinalNumber constructor.
     * @param string $name
     * @param int $modulo
     * @param int $offset
     * @param bool $printOnes
     * @param bool $invariable
     */
    public function __construct(string $name, int $modulo, int $offset, bool $printOnes = true, bool $invariable = false)
    {
        $this->name = $name;
        $this->modulo = $modulo;
        $this->offset = $offset;
        $this->invariable = $invariable;
        $this->printOnes = $printOnes;
    }

    /**
     * Is a valid cardinal for the number of digits?
     * @param int $nbDigits
     * @return bool
     */
    private function isValid(int $nbDigits): bool
    {
        return ($nbDigits - $this->offset) % $this->modulo === 0;
    }

    /**
     * Apply the cardinal if valid
     * @param int $nbDigits
     * @param int $firstDigit
     * @param string $humanizedInt
     * @return string
     */
    public function apply(int $nbDigits, int $firstDigit, string $humanizedInt): string
    {
        if (!$this->isValid($nbDigits)) {
            return $humanizedInt;
        }
        $humanized = $humanizedInt;
        $specialPart = $this->name;
        if ($firstDigit === 1 && !$this->printOnes) {
            $humanized = '';
        } elseif ($firstDigit > 1 && !$this->invariable) {
            $specialPart .= 's';
        }
        if (!empty($humanized)) {
            $humanized .= ' ';
        }
        return $humanized . $specialPart;
    }
}