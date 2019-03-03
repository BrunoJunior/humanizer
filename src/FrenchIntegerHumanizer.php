<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 26/02/19
 * Time: 21:25
 */

namespace Humanizer;

use Humanizer\tools\FrIntBlocHumanizer;
use Humanizer\tools\Stringer;

/**
 * Class FrenchIntegerHumanizer
 * Humanize integer to french
 * @package Humanizer
 */
class FrenchIntegerHumanizer implements IHumanizer
{
    const THOUSANDS = ['', 'mille', 'million', 'milliard'];

    /**
     * @var int
     */
    private $number;

    /**
     * @var Stringer
     */
    private $output;

    /**
     * @var int
     */
    private $nbThousands = 0;

    /**
     * FrenchIntegerHumanizer constructor.
     * Init specialParts array
     * @param int $number
     */
    public function __construct(int $number)
    {
        $this->number = $number;
        $this->output = new Stringer('');
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
        $isNegative = $this->number < 0;
        if ($isNegative) {
            $this->number = -$this->number;
        }
        $this->humanizeBlocs();
        if ($isNegative) {
            $this->output->prefix('moins ');
        }
        return trim($this->output);
    }

    /**
     * Cut each 3 digits, humanize bloc and do it again until the bloc is not empty
     */
    private function humanizeBlocs()
    {
        // Getting the first right bloc of 3 digits
        $bloc = $this->number % 1000;
        // The rest to humanize
        $this->number = intdiv($this->number, 1000);
        $step = $this->nbThousands > 0 ? (($this->nbThousands -1) % 3) + 1 : 0;
        // If the bloc's value is 0 we don't have to write anything
        if ($bloc > 0 || $step === 3) {
            // Getting the step's bloc ("mille", "million", "milliard" ...)
            $blocStep = new Stringer(static::THOUSANDS[$step]);
            if ($bloc > 1 && $step > 1) {
                // Pluralize the step if it's more than 1 and it's not mille
                $blocStep->pluralize();
            }
            $this->output
                ->prefix(Stringer::SPACE)
                ->prefix($blocStep);
            if ($bloc > 1 || $step !== 1) {
                $this->output
                    ->prefix(Stringer::SPACE)
                    ->prefix((new FrIntBlocHumanizer($bloc))->humanize());
            }
        }
        $this->nbThousands++;
        // Do it again ...
        if ($this->number > 0) {
            $this->humanizeBlocs();
        }
    }
}
