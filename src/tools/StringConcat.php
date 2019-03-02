<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 02/03/19
 * Time: 00:26
 */

namespace Humanizer\tools;


class StringConcat
{
    const SPACE = ' ';
    const EMPTY = '';
    const PLURAL = 's';
    const SEPARATOR = '-';

    /**
     * @var string
     */
    private $string;

    /**
     * StringConcat constructor.
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     * @param string $string
     * @param bool $ifEmpty
     * @return $this|StringConcat
     */
    public function concat(string $string, bool $ifEmpty = true): self
    {
        if ($ifEmpty && empty($this)) {
            return $this;
        }
        return $this->concatWithPrefix($string, static::EMPTY);
    }

    /**
     * @param bool $ifEmpty
     * @return StringConcat
     */
    public function addSpace(bool $ifEmpty = true): self
    {
        return $this->concat(static::SPACE, $ifEmpty);
    }

    /**
     * @param bool $ifEmpty
     * @return StringConcat
     */
    public function addSeparator(bool $ifEmpty = true): self
    {
        return $this->concat(static::SEPARATOR, $ifEmpty);
    }

    /**
     * @param string $string
     * @param string $prefix
     * @return StringConcat
     */
    public function concatWithPrefix(string $string, string $prefix = self::SPACE): self
    {
        if (!empty($string)) {
            $this->string = $this . $prefix . $string;
        }
        return $this;
    }

    /**
     * @param bool $ifEmpty
     * @return StringConcat
     */
    public function pluralize(bool $ifEmpty = true): self
    {
        return $this->concat(static::PLURAL, $ifEmpty);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->string;
    }
}