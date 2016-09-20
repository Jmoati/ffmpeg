<?php

namespace Jmoati\FFMpeg\Filter;

class RotationFilter extends FilterAbstract implements FormatFilterInterface, FrameFilterInterface
{
    const ROTATION_90 = 'transpose=1';
    const ROTATION_180 = 'transpose=1, transpose=1';
    const ROTATION_270 = 'transpose=2';

    /**
     * @var string
     */
    protected $rotation;

    /**
     * RotationFilter constructor.
     *
     * @param string $rotation
     */
    public function __construct(string $rotation)
    {
        $this->rotation = $rotation;
    }

    /**
     * @return string
     */
    public function getRotation() : string
    {
        return $this->rotation;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return sprintf('-vf "%s"', $this->rotation);
    }
}
