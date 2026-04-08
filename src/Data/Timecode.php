<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

final class Timecode
{
    public function __construct(
        private int $frames = 0,
        private int $seconds = 0,
        private int $minutes = 0,
        private int $hours = 0,
    ) {
    }

    public function __toString(): string
    {
        return sprintf('%02d:%02d:%02d.%02d', $this->hours, $this->minutes, $this->seconds, $this->frames);
    }

    public static function createFromFrame(int $frames, float $fps): self
    {
        return self::create()->fromFrame($frames, $fps);
    }

    public function fromFrame(int $frames, float $fps): self
    {
        return $this->fromSeconds($frames / $fps);
    }

    public function fromSeconds(float $seconds): self
    {
        $left = floor($seconds);
        $this->frames = (int) round(100 * ($seconds - $left));
        $this->seconds = (int) ($left % 60);

        $left = (int) (($left - $this->seconds) / 60);

        $this->minutes = $left % 60;
        $this->hours = (int) (($left - $this->minutes) / 60);

        return $this;
    }

    public static function create(): self
    {
        return new self();
    }

    public static function createFromSeconds(float $seconds): self
    {
        return self::create()->fromSeconds($seconds);
    }

    public static function createFromString(string $string): self
    {
        return self::create()->fromString($string);
    }

    public function fromString(string $string): self
    {
        if (!preg_match('/^([0-9]+):([0-9]+):([0-9]+)[:,\.]{1}([0-9]+)$/', $string, $matches)) {
            throw new \RuntimeException(sprintf('Cannot parse timecode from string "%s".', $string));
        }

        $this->hours = (int) $matches[1];
        $this->minutes = (int) $matches[2];
        $this->seconds = (int) $matches[3];
        $this->frames = (int) $matches[4];

        return $this;
    }

    public function add(self $timecode): self
    {
        return $this->fromSeconds($this->getSeconds() + $timecode->getSeconds());
    }

    public function getSeconds(): float
    {
        return $this->hours * 3600 + $this->minutes * 60 + $this->seconds + $this->frames / 100;
    }

    public function subtract(self $timecode): self
    {
        return $this->fromSeconds($this->getSeconds() - $timecode->getSeconds());
    }
}
