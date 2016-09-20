<?php

namespace Jmoati\FFMpeg\Data;

class Timecode
{
    /**
     * @var int
     */
    protected $hours;

    /**
     * @var int
     */
    protected $minutes;

    /**
     * @var int
     */
    protected $seconds;

    /**
     * @var int
     */
    protected $frames;

    /**
     * Timecode constructor.
     *
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
     * @return Timecode
     */
    public static function create() : Timecode
    {
        return new static();
    }

    /**
     * @param int   $frames
     * @param float $fps
     *
     * @return Timecode
     */
    public static function createFromFrame(int $frames, float $fps) : Timecode
    {
        return self::create()->fromFrame($frames, $fps);
    }

    /**
     * @param float $secondes
     *
     * @return Timecode
     */
    public static function createFromSeconds(float $secondes) : Timecode
    {
        return self::create()->fromSeconds($secondes);
    }

    /**
     * @param string $string
     *
     * @return Timecode
     */
    public static function createFromString(string $string) : Timecode
    {
        return self::create()->fromString($string);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return sprintf('%02d:%02d:%02d.%02d', $this->hours, $this->minutes, $this->seconds, $this->frames);
    }

    /**
     * @param int   $frames
     * @param float $fps
     *
     * @return Timecode
     */
    public function fromFrame(int $frames, float $fps) : Timecode
    {
        return $this->fromSeconds($frames / $fps);
    }

    /**
     * @param float $seconds
     *
     * @return Timecode
     */
    public function fromSeconds(float $seconds) : Timecode
    {
        $left = floor($seconds);
        $this->frames = round(100 * ($seconds - $left));
        $this->seconds = $left % 60;

        $left = ($left - $this->seconds) / 60;

        $this->minutes = $left % 60;
        $this->hours = ($left - $this->minutes) / 60;

        return $this;
    }

    /**
     * @param string $string
     *
     * @return Timecode
     */
    public function fromString(string $string) : Timecode
    {
        preg_match('/^([0-9]+):([0-9]+):([0-9]+)[:,\.]{1}([0-9]+)$/', $string, $matches);

        $this->hours = $matches[1];
        $this->minutes = $matches[2];
        $this->seconds = $matches[3];
        $this->frames = $matches[4];

        return $this;
    }
}
