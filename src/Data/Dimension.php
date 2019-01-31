<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

class Dimension
{
    /** @var int */
    protected $width;

    /** @var int */
    protected $height;

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function __toString(): string
    {
        return sprintf('%sx%s', $this->width, $this->height);
    }

    public static function create(int $width, int $height): self
    {
        return new static($width, $height);
    }

    public static function createFromString(string $string): self
    {
        preg_match('/([0-9]+)\s?[:xX,;]{1}\s?([0-9]+)/', $string, $matches);

        return self::create((int) $matches[1], (int) $matches[2]);
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height, int $modulo = 2): self
    {
        $this->height = (int) (floor($height) - (floor($height) % $modulo));

        return $this;
    }

    public function setWidth(int $width, int $modulo = 2): self
    {
        $this->width = (int) (floor($width) - (floor($width) % $modulo));

        return $this;
    }

    public function getRatio(): float
    {
        return $this->width / $this->height;
    }
}
