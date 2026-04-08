<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg;

use Jmoati\FFMpeg\Data\Media;
use Symfony\Component\Process\Process;

final class FFMpeg implements FFInterface
{
    private readonly string $bin;

    public function __construct(
        private readonly FFProbe $ffprobe,
    ) {
        $process = new Process(['which', 'ffmpeg']);
        $process->run();

        if ($process->getExitCode() > 0) {
            throw new \RuntimeException('no ffmpeg binary found');
        }

        $this->bin = mb_trim($process->getOutput());
    }

    public static function createFile(): Media
    {
        return new Media(self::create());
    }

    public static function create(?FFProbe $ffprobe = null): self
    {
        return new self($ffprobe ?? new FFProbe());
    }

    public static function openFile(string $filename): Media
    {
        return self::create()->ffprobe->media($filename);
    }

    /** @param list<string|int> $command */
    public function run(array $command, ?callable $callback = null): Process
    {
        $process = new Process(array_merge([$this->bin], $command), timeout: 0.0);
        $process->run($callback);

        return $process;
    }
}
