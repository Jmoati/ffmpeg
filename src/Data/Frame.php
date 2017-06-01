<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

class Frame extends ManipulableAbstract
{
    /** @var Timecode */
    protected $timecode;

    /**
     * @param Media    $media
     * @param Timecode $timecode
     */
    public function __construct(Media $media, Timecode $timecode)
    {
        $this->media = $media;
        $this->timecode = $timecode;

        parent::__construct();
    }

    /**
     * @param string $filename
     * @param bool   $accurate
     *
     * @return bool
     */
    public function save(string $filename, bool $accurate = false): bool
    {
        if (false === $accurate) {
            $command = sprintf(
                '-y -ss %s -i "%s" %s -vframes 1 -f image2 "%s"',
                $this->timecode,
                $this->media->format()->getFilename(),
                (string) $this->filters(),
                $filename
            );
        } else {
            $command = sprintf(
                '-y -i "%s" %s -vframes 1 -ss %s -f image2 "%s"',
                $this->media->format()->getFilename(),
                (string) $this->filters(),
                $this->timecode,
                $filename
            );
        }

        $process = $this->media->ffmpeg()->run($command);

        return 0 === $process->getExitCode();
    }
}
