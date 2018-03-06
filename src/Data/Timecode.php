<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

class Timecode
{
    /** @var int */
    protected $hours = 0;

    /** @var int */
    protected $minutes = 0;

    /** @var int */
    protected $seconds = 0;

    /** @var int */
    protected $frames = 0;

    /**
     * @param int $frames
     * @param int $seconds
     * @param int $minutes
     * @param int $hours
     */
    public function __construct(int $frames = 0, int $seconds = 0, int $minutes = 0, int $hours = 0)
    {
        $this->frames = $frames;
        $this->seconds = $seconds;
        $this->minutes = $minutes;
        $this->hours = $hours;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%02d:%02d:%02d.%02d', $this->hours, $this->minutes, $this->seconds, $this->frames);
    }

    /**
     * @return Timecode
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * @param int   $frames
     * @param float $fps
     *
     * @return Timecode
     */
    public static function createFromFrame(int $frames, float $fps): self
    {
        return self::create()->fromFrame($frames, $fps);
    }

    /**
     * @param float $secondes
     *
     * @return Timecode
     */
    public static function createFromSeconds(float $secondes): self
    {
        return self::create()->fromSeconds($secondes);
    }

    /**
     * @param string $string
     *
     * @return Timecode
     */
    public static function createFromString(string $string): self
    {
        return self::create()->fromString($string);
    }

    /**
     * @param int   $frames
     * @param float $fps
     *
     * @return Timecode
     */
    public function fromFrame(int $frames, float $fps): self
    {
        return $this->fromSeconds($frames / $fps);
    }

    /**
     * @param float $seconds
     *
     * @return Timecode
     */
    public function fromSeconds(float $seconds): self
    {
        $left = floor($seconds);
        $this->frames = (int) round(100 * ($seconds - $left));
        $this->seconds = $left % 60;

        $left = (int) (($left - $this->seconds) / 60);

        $this->minutes = $left % 60;
        $this->hours = (int) (($left - $this->minutes) / 60);

        return $this;
    }

    /**
     * @param Timecode $timecode
     *
     * @return Timecode
     */
    public function add(self $timecode): self
    {
        $this->fromSeconds($this->getSeconds() + $timecode->getSeconds());

        return $this;
    }

    /**
     * @param Timecode $timecode
     *
     * @return Timecode
     */
    public function subtract(self $timecode): self
    {
        $this->fromSeconds($this->getSeconds() - $timecode->getSeconds());

        return $this;
    }

    /**
     * @return float
     */
    public function getSeconds(): float
    {
        return $this->hours * 3600 + $this->minutes * 60 + $this->seconds + $this->frames / 100;
    }

    /**
     * @param string $string
     *
     * @return Timecode
     */
    public function fromString(string $string): self
    {
        preg_match('/^([0-9]+):([0-9]+):([0-9]+)[:,\.]{1}([0-9]+)$/', $string, $matches);

        $this->hours = (int) $matches[1];
        $this->minutes = (int) $matches[2];
        $this->seconds = (int) $matches[3];
        $this->frames = (int) $matches[4];

        return $this;
    }
}
