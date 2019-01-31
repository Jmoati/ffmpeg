<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\Timecode;

final class ClipFilter extends FilterAbstract implements FormatFilterInterface, StreamFilterInterface
{
    /** @var Timecode|null */
    protected $start;

    /** @var Timecode|null */
    protected $duration;

    /**
     * @param Timecode|null $duration
     * @param Timecode|null $start
     */
    public function __construct(Timecode $duration = null, Timecode $start = null)
    {
        $this->start = $start;
        $this->duration = $duration;
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
