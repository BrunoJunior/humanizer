<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 02/03/19
 * Time: 00:26
 */

namespace Humanizer\tools;


class Stringer
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
     * @return $this|Stringer
     */
    public function concat(string $string, bool $ifEmpty = true): self
    {
        if ($ifEmpty || !$this->isEmpty()) {
            $this->concatWithPrefix($string, static::EMPTY);
        }
        return $this;
    }

    /**
     * @param string $string
     * @param bool $ifEmpty
     * @return Stringer
     */
    public function prefix(string $string, bool $ifEmpty = true): self
    {
        if ($ifEmpty || !$this->isEmpty()) {
            $this->string = $string . $this;
        }
        return $this;
    }

    /**
     * @param bool $ifEmpty
     * @return Stringer
     */
    public function addSpace(bool $ifEmpty = true): self
    {
        return $this->concat(static::SPACE, $ifEmpty);
    }

    /**
     * @param bool $ifEmpty
     * @return Stringer
     */
    public function addSeparator(bool $ifEmpty = true): self
    {
        return $this->concat(static::SEPARATOR, $ifEmpty);
    }

    /**
     * @param string $string
     * @param string $prefix
     * @return Stringer
     */
    public function concatWithPrefix(string $string, string $prefix = self::SPACE): self
    {
        if ($string !== static::EMPTY) {
            $this->string = $this . $prefix . $string;
        }
        return $this;
    }

    /**
     * @param bool $ifEmpty
     * @return Stringer
     */
    public function pluralize(bool $ifEmpty = true): self
    {
        return $this->concat(static::PLURAL, $ifEmpty);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->string === static::EMPTY;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->string;
    }
}