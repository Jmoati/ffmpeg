<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg;

use Jmoati\FFMpeg\Data\Media;
use Symfony\Component\Process\Process;

final class FFMpeg implements FFInterface
{
    /** @var FFProbe FProbe */
    private $ffprobe;

    /** @var string */
    private $bin;

    public function __construct(FFProbe $ffprobe)
    {
        $this->ffprobe = $ffprobe;

        if (file_exists(__DIR__.'/../vendor/bin/ffmpeg')) {
            $this->bin = (string) realpath(__DIR__.'/../vendor/bin/ffmpeg');
        } elseif (file_exists(__DIR__.'/../../../bin/ffmpeg')) {
            $this->bin = (string) realpath(__DIR__.'/../../../bin/ffmpeg');
        } else {
            $process = new Process(['which',  'ffmpeg']);
            $process->run();

            if ($process->getExitCode() < 1) {
                $this->bin = 'ffmpeg';
            } else {
                throw new \Exception('no ffmpeg binary found');
            }
        }
    }

    public static function create(FFProbe $ffprobe = null): self
    {
        if (null === $ffprobe) {
            $ffprobe = new FFProbe();
        }

        return new static($ffprobe);
    }

    public static function createFile(): Media
    {
        return new Media(self::create());
    }

    public static function openFile(string $filename): Media
    {
        return self::create()->ffprobe->media($filename);
    }

    public function run(array $command, callable $callback = null): Process
    {
        $process = new Process(array_merge([$this->bin], $command), null, null, null, 0.0);
        $process->run($callback);

        return $process;
    }
}
