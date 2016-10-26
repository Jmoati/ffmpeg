<?php

namespace Jmoati\FFMpeg\Data;

use Jmoati\FFMpeg\Builder\CommandBuilder;
use Jmoati\FFMpeg\FFMpeg;
use Symfony\Component\Filesystem\Filesystem;

class Media
{
    /**
     * @var StreamCollection
     */
    protected $streams;

    /**
     * @var Format
     */
    protected $format;

    /**
     * @var FFmpeg
     */
    protected $ffmpeg;

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * Media constructor.
     *
     * @param FFMpeg                $ffmpeg
     * @param StreamCollection|null $streams
     * @param Format|null           $format
     */
    public function __construct(FFMpeg $ffmpeg, StreamCollection $streams = null, Format $format = null)
    {
        $this->fs = new Filesystem();
        $this->ffmpeg = $ffmpeg;

        if (null === $streams) {
            $this->streams = new StreamCollection();
        } else {
            $this->streams = $streams;
        }

        $this->streams->setMedia($this);

        if (null === $format) {
            $this->format = new Format();
        } else {
            $this->format = $format;
        }

        $this->format->setMedia($this);
    }

    /**
     * @return StreamCollection
     */
    public function streams() : StreamCollection
    {
        return $this->streams;
    }

    /**
     * @return Format
     */
    public function format() : Format
    {
        return $this->format;
    }

    /**
     * @return FFMpeg
     */
    public function ffmpeg() : FFMpeg
    {
        return $this->ffmpeg;
    }

    /**
     * @param Timecode $timecode
     *
     * @return Frame
     */
    public function frame(Timecode $timecode) : Frame
    {
        return new Frame($this, $timecode);
    }

    /**
     * @param Output $output
     *
     * @return int
     */
    public function getFrameCount(Output $output) : int
    {
        $commandBuilder = new CommandBuilder($this, $output, true);
        $frames = 0;

        $this->ffmpeg->run(
            sprintf(
                '%s %s %s "%s" -y',
                $commandBuilder->computeInputs(),
                $commandBuilder->computeFormatFilters(),
                $commandBuilder->computeParams(),
                '/dev/null'
            ),
            function ($type, $buffer) use (&$frames) {
                if (preg_match('/frame=\s*([0-9]+)\s/', $buffer, $matches)) {
                    $frames = $matches[1];
                }
            }
        );

        return $frames + 1;
    }

    /**
     * @param callable|null $callback
     * @param string        $property
     * @param int           $value
     *
     * @return Media
     */
    protected function setCallbackProperty($callback, string $property, int $value) : Media
    {
        if (null !== $callback && property_exists($callback, $property)) {
            $callback->$property = $value;
        }

        return $this;
    }

    /**
     * @param string        $filename
     * @param Output        $output
     * @param callable|null $callback
     *
     * @return Media
     */
    public function save(string $filename, Output $output, callable $callback = null) : Media
    {
        $commandBuilder = new CommandBuilder($this, $output);
        $tmpDir = sys_get_temp_dir().'/'.sha1(uniqid()).'/';

        $this->fs->mkdir($tmpDir);

        $passes = $output->getPasses();

        $this->setCallbackProperty($callback, 'totalPasses', $passes);

        for ($i = 0, $l = $passes; $i < $l; ++$i) {
            if (null !== $callback) {
                $this
                    ->setCallbackProperty($callback, 'currentPass', $i + 1)
                    ->setCallbackProperty($callback, 'currentFrame', 0)
                    ->setCallbackProperty($callback, 'totalFrames', $this->getFrameCount($output));
            }

            $this->ffmpeg->run(
                sprintf(
                    '%s %s %s %s "%s" -y',
                    $commandBuilder->computeInputs(),
                    $commandBuilder->computePasses($i, $passes, $tmpDir),
                    $commandBuilder->computeFormatFilters(),
                    $commandBuilder->computeParams(),
                    $filename
                ),
                get_class($callback) == 'Closure' || null === $callback ?
                    $callback :
                    array($callback, 'callback')
            );
        }

        $this->fs->remove($tmpDir);

        return $this;
    }
}
