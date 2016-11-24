<?php

namespace Jmoati\FFMpeg;

use Jmoati\FFMpeg\Data\Media;
use Symfony\Component\Process\Process;

class FFMpeg implements FFInterface
{
    /**
     * @var FFProbe
     */
    protected $ffprobe;

    /**
     * @var string
     */
    protected $bin;

    /**
     * @param FFProbe $ffprobe
     *
     * @return FFMpeg
     */
    public static function create(FFProbe $ffprobe = null) : FFMpeg
    {
        if (null === $ffprobe) {
            $ffprobe = new FFProbe();
        }

        return new static($ffprobe);
    }

    /**
     * FFMpeg constructor.
     *
     * @param FFProbe $ffprobe
     */
    public function __construct(FFProbe $ffprobe)
    {
        $this->ffprobe = $ffprobe;

        $proccess = new Process('which ffmpeg');
        $proccess->run();

        if (0 == $proccess->getExitCode()) {
            $this->bin = 'ffmpeg';
        } elseif (file_exists(__DIR__.'/../vendor/bin/ffmpeg')) {
            $this->bin = realpath(__DIR__.'/../vendor/bin/ffmpeg');
        } else {
            $this->bin = realpath(__DIR__.'/../../../bin/ffmpeg');
        }
    }

    /**
     * @return Media
     */
    public static function createFile() : Media
    {
        $ffmpeg = self::create();

        return new Media($ffmpeg);
    }

    /**
     * @param string $filename
     *
     * @return Media
     */
    public static function openFile(string $filename) : Media
    {
        return self::create()->ffprobe->media($filename);
    }

    /**
     * @param string        $command
     * @param callable|null $callback
     *
     * @return Process
     */
    public function run(string $command, $callback = null) : Process
    {
        $process = new Process('nice '.$this->bin.' '.$command, null, null, null, 0);
        $process->run($callback);

        return $process;
    }
}