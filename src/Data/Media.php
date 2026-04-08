<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

use Jmoati\FFMpeg\Builder\CommandBuilder;
use Jmoati\FFMpeg\FFMpeg;
use Jmoati\FFMpeg\Progress\ProgressInterface;
use Symfony\Component\Filesystem\Filesystem;

final class Media
{
    private readonly StreamCollection $streams;
    private readonly Format $format;
    private readonly Filesystem $filesystem;

    public function __construct(
        private readonly FFMpeg $ffmpeg,
        ?StreamCollection $streams = null,
        ?Format $format = null,
    ) {
        $this->filesystem = new Filesystem();

        $this->streams = $streams ?? new StreamCollection();
        $this->streams->setMedia($this);

        $this->format = $format ?? new Format();
        $this->format->setMedia($this);
    }

    public function streams(): StreamCollection
    {
        return $this->streams;
    }

    public function format(): Format
    {
        return $this->format;
    }

    public function ffmpeg(): FFMpeg
    {
        return $this->ffmpeg;
    }

    public function frame(Timecode $timecode): Frame
    {
        return new Frame($this, $timecode);
    }

    public function save(string $filename, Output $output, ?ProgressInterface $callback = null): bool
    {
        $commandBuilder = new CommandBuilder($this, $output);
        $tmpDir = sys_get_temp_dir().'/'.sha1(uniqid()).'/';

        $this->filesystem->mkdir($tmpDir);

        $passes = $output->getPasses();

        $callback?->setTotalPasses($passes);

        $process = null;

        for ($i = 0; $i < $passes; ++$i) {
            if (null !== $callback) {
                $callback->setCurrentPass($i + 1);
                $callback->setCurrentFrame(0);
                $callback->setTotalFrames($this->getFrameCount($output));
            }

            $process = $this->ffmpeg->run(
                array_merge(
                    $commandBuilder->computeInputs(),
                    $commandBuilder->computePasses($i, $passes, $tmpDir),
                    $commandBuilder->computeFormatFilters(),
                    $commandBuilder->computeParams(),
                    [$filename],
                    ['-y']
                ),
                $callback
            );

            if (0 !== $process->getExitCode()) {
                break;
            }
        }

        $this->filesystem->remove($tmpDir);

        if (null === $process) {
            throw new \LogicException('No encoding pass was executed.');
        }

        return 0 === $process->getExitCode();
    }

    public function getFrameCount(Output $output): int
    {
        $commandBuilder = new CommandBuilder($this, $output, true);
        $frames = 0;

        $this->ffmpeg->run(
            array_merge(
                $commandBuilder->computeInputs(),
                $commandBuilder->computeFormatFilters(),
                $commandBuilder->computeParams(),
                ['/dev/null'],
                ['-y']
            ),
            static function (string $type, string $buffer) use (&$frames): void {
                if (preg_match('/frame=\s*([0-9]+)\s/', $buffer, $matches)) {
                    $frames = (int) $matches[1];
                }
            }
        );

        // +1 because ffmpeg reports frames already processed; the last frame may not appear in output
        return $frames + 1;
    }
}
