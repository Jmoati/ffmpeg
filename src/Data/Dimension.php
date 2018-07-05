<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

class Dimension
{
    /** @var int */
    protected $width;

    /** @var int */
    protected $height;

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%sx%s', $this->width, $this->height);
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @return Dimension
     */
    public static function create(int $width, int $height): self
    {
        return new static($width, $height);
    }

    /**
     * @param string $string
     *
     * @return Dimension
     */
    public static function createFromString(string $string): self
    {
        preg_match('/([0-9]+)\s?[:xX,;]{1}\s?([0-9]+)/', $string, $matches);

        return self::create((int) $matches[1], (int) $matches[2]);
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @param int $modulo
     *
     * @return Dimension
     */
    public function setHeight(int $height, int $modulo = 2): self
    {
        $this->height = (int) (floor($height) - (floor($height) % $modulo));

        return $this;
    }

    /**
     * @param int $width
     * @param int $modulo
     *
     * @return Dimension
     */
    public function setWidth(int $width, int $modulo = 2): self
    {
        $this->width = (int) (floor($width) - (floor($width) % $modulo));

        return $this;
    }

    /**
     * @return float
     */
    public function getRatio(): float
    {
        return $this->width / $this->height;
    }
}
