<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg;

use Jmoati\FFMpeg\Data\Media;
use Symfony\Component\Process\Process;

final class FFMpeg implements FFInterface
{
    private string $bin;

    public function __construct(
        private readonly FFProbe $ffprobe
    ) {
        $process = new Process(['which', 'ffmpeg']);
        $process->run();

        if ($process->getExitCode() > 0) {
            throw new \Exception('no ffmpeg binary found');
        }

        $this->bin = str_replace(\PHP_EOL, '', $process->getOutput());
    }

    public static function createFile(): Media
    {
        return new Media(self::create());
    }

    public static function create(FFProbe $ffprobe = null): self
    {
        if (null === $ffprobe) {
            $ffprobe = new FFProbe();
        }

        return new static($ffprobe);
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
