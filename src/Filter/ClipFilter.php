<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\Timecode;

final class ClipFilter extends FilterAbstract implements FormatFilterInterface, StreamFilterInterface
{
    public function __construct(
        private readonly ?Timecode $duration = null,
        private readonly ?Timecode $start = null,
    ) {
    }

    /** @return list<string> */
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
