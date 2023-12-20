<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

use Jmoati\FFMpeg\Builder\CommandBuilder;
use Jmoati\FFMpeg\FFMpeg;
use Jmoati\FFMpeg\Progress\ProgressInterface;
use Symfony\Component\Filesystem\Filesystem;

final class Media
{
    private StreamCollection $streams;
    private Format $format;
    private Filesystem $filesystem;

    public function __construct(
        private FFMpeg $ffmpeg,
        StreamCollection $streams = null,
        Format $format = null
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

    public function save(string $filename, Output $output, ProgressInterface $callback = null): bool
    {
        $commandBuilder = new CommandBuilder($this, $output);
        $tmpDir = sys_get_temp_dir().'/'.sha1(uniqid()).'/';

        $this->filesystem->mkdir($tmpDir);

        $passes = $output->getPasses();

        if (null !== $callback) {
            $this->setCallbackProperty($callback, 'totalPasses', $passes);
        }

        $process = null;

        for ($i = 0, $l = $passes; $i < $l; ++$i) {
            if (null !== $callback) {
                $this
                    ->setCallbackProperty($callback, 'currentPass', $i + 1)
                    ->setCallbackProperty($callback, 'currentFrame', 0)
                    ->setCallbackProperty($callback, 'totalFrames', $this->getFrameCount($output));
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
            throw new \LogicException();
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
            function ($type, $buffer) use (&$frames) {
                if (preg_match('/frame=\s*([0-9]+)\s/', $buffer, $matches)) {
                    $frames = (int) $matches[1];
                }
            }
        );

        return $frames + 1;
    }

    private function setCallbackProperty(ProgressInterface $callback, string $property, int $value): self
    {
        if (property_exists($callback, $property)) {
            $callback->$property = $value;
        }

        return $this;
    }
}
