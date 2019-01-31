<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

class Frame extends AbstractManipulable
{
    /** @var Timecode */
    protected $timecode;

    public function __construct(Media $media, Timecode $timecode)
    {
        $this->media = $media;
        $this->timecode = $timecode;

        parent::__construct();
    }

    public function save(string $filename, bool $accurate = false): bool
    {
        if (null === $this->media) {
            return false;
        }

        $filters = [];

        foreach ($this->filters() as $filter) {
            array_merge($filters, $filter->__toArray());
        }

        if (false === $accurate) {
            $command = array_merge(
                ['-y', '-ss', $this->timecode,  '-i',  $this->media->format()->getFilename()],
                $filters,
                ['-vframes',  1,  '-f',  'image2', $filename]
            );
        } else {
            $command = array_merge(
                ['-y', '-i',  $this->media->format()->getFilename()],
                $filters,
                ['-vframes',  1,  '-ss', $this->timecode, '-f',  'image2', $filename]
            );
        }

        $process = $this->media->ffmpeg()->run($command);

        return $process->getExitCode() < 1;
    }
}
