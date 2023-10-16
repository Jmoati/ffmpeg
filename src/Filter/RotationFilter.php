<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Filter;

final class RotationFilter extends FilterAbstract implements FormatFilterInterface, FrameFilterInterface
{
    public const ROTATION_90 = 'transpose=1';
    public const ROTATION_180 = 'transpose=1, transpose=1';
    public const ROTATION_270 = 'transpose=2';

    public function __construct(
        private readonly string $rotation
    ) {
        if (!in_array($rotation, [self::ROTATION_90, self::ROTATION_180, self::ROTATION_270], true)) {
            throw new \LogicException('$rotation must be an ROTATION_X constant');
        }
    }

    public function __toArray(): array
    {
        return ['-vf', $this->rotation];
    }

    public function getRotation(): string
    {
        return $this->rotation;
    }
}
