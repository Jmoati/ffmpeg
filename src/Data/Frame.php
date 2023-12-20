<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

use Jmoati\FFMpeg\Filter\FilterInterface;

class Frame extends AbstractManipulable
{
    public function __construct(
        Media $media,
        protected Timecode $timecode
    ) {
        $this->media = $media;

        parent::__construct();
    }

    public function save(string $filename, bool $accurate = false): bool
    {
        if (null === $this->media) {
            return false;
        }

        $filters = [];

        /** @var FilterInterface $filter */
        foreach ($this->filters() as $filter) {
            $filters += $filter->__toArray();
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
