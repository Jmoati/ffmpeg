<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg;

use Jmoati\FFMpeg\Data\Media;
use Symfony\Component\Process\Process;

final class FFMpeg implements FFInterface
{
    /** @var FFProbe */
    private $ffprobe;

    /** @var string */
    private $bin;

    /**
     * @param FFProbe $ffprobe
     */
    public function __construct(FFProbe $ffprobe)
    {
        $this->ffprobe = $ffprobe;

        if (file_exists(__DIR__.'/../vendor/bin/ffmpeg')) {
            $this->bin = realpath(__DIR__.'/../vendor/bin/ffmpeg');
        } elseif (file_exists(__DIR__.'/../../../bin/ffmpeg')) {
            $this->bin = realpath(__DIR__.'/../../../bin/ffmpeg');
        } else {
            $process = new Process('which ffmpeg');
            $process->run();

            if ($process->getExitCode() < 1) {
                $this->bin = 'ffmpeg';
            } else {
                throw new \Exception('no ffmpeg binary found');
            }
        }
    }

    /**
     * @param FFProbe|null $ffprobe
     *
     * @return FFMpeg
     */
    public static function create(FFProbe $ffprobe = null): self
    {
        if (null === $ffprobe) {
            $ffprobe = new FFProbe();
        }

        return new static($ffprobe);
    }

    /**
     * @return Media
     */
    public static function createFile(): Media
    {
        return new Media(self::create());
    }

    /**
     * @param string $filename
     *
     * @throws \Exception
     *
     * @return Media
     */
    public static function openFile(string $filename): Media
    {
        return self::create()->ffprobe->media($filename);
    }

    /**
     * @param string        $command
     * @param callable|null $callback
     *
     * @return Process
     */
    public function run(string $command, callable $callback = null): Process
    {
        $process = new Process('nice '.$this->bin.' '.$command, null, null, null, 0);
        $process->run($callback);

        return $process;
    }
}
