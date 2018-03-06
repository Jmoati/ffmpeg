<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\Timecode;

final class ClipFilter extends FilterAbstract implements FormatFilterInterface, StreamFilterInterface
{
    /** @var Timecode|null */
    private $start;

    /** @var Timecode|null */
    private $duration;

    /**
     * @param null|Timecode $duration
     * @param null|Timecode $start
     */
    public function __construct(Timecode $duration = null, Timecode $start = null)
    {
        $this->start = $start;
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $result = [];

        if (null !== $this->start) {
            $result[] = sprintf('-ss %s', $this->start);
        }

        if (null !== $this->duration) {
            $result[] = sprintf('-t %s', $this->duration);
        }

        return implode(' ', $result);
    }
}
