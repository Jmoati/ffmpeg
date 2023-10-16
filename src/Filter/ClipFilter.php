<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\Timecode;

final class ClipFilter extends FilterAbstract implements FormatFilterInterface, StreamFilterInterface
{
    public function __construct(
        protected ?Timecode $duration = null,
        protected ?Timecode $start = null
    ) {
    }

    public function __toArray(): array
    {
        $result = [];

        if (null !== $this->start) {
            $result[] = '-ss';
            $result[] = (string) $this->start;
        }

        if (null !== $this->duration) {
            $result[] = '-t';
            $result[] = (string) $this->duration;
        }

        return $result;
    }
}
