<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Filter;

final class RotationFilter extends FilterAbstract implements FormatFilterInterface, FrameFilterInterface
{
    public const ROTATION_90 = 'transpose=1';
    public const ROTATION_180 = 'transpose=1, transpose=1';
    public const ROTATION_270 = 'transpose=2';

    /** @var string */
    protected $rotation = '';

    /**
     * @param string $rotation
     */
    public function __construct(string $rotation)
    {
        if (!in_array($rotation, [self::ROTATION_90, self::ROTATION_180, self::ROTATION_270], true)) {
            throw new \LogicException();
        }

        $this->rotation = $rotation;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('-vf "%s"', $this->rotation);
    }

    /**
     * @return string
     */
    public function getRotation(): string
    {
        return $this->rotation;
    }
}
