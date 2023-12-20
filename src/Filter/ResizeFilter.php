<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\Dimension;

final class ResizeFilter extends FilterAbstract implements FormatFilterInterface, FrameFilterInterface
{
    public const MODE_FORCE = 0;
    public const MODE_INSET = 1;
    public const MODE_MAX_WIDTH = 2;
    public const MODE_MAX_HEIGHT = 4;

    public function __construct(
        private readonly Dimension $dimension,
        private readonly int $mode = self::MODE_INSET
    ) {
        if (!in_array($mode, [self::MODE_FORCE, self::MODE_INSET, self::MODE_MAX_HEIGHT, self::MODE_MAX_WIDTH], true)) {
            throw new \LogicException('$mode must be MODE_X constant');
        }
    }

    public function __toArray(): array
    {
        return ['-s', (string) $this->compute()];
    }

    protected function compute(): ?Dimension
    {
        if (null === $this->media()) {
            return null;
        }

        $source = $this->media()->streams()->videos()->first();

        if (false === $source) {
            return null;
        }

        $sourceDimension = new Dimension($source->getInt('width'), $source->getInt('height'));

        if (self::MODE_MAX_HEIGHT === $this->mode || (self::MODE_INSET === $this->mode && $this->dimension->getRatio() > $sourceDimension->getRatio())) {
            $this->dimension->setWidth((int) floor($this->dimension->getHeight() * $sourceDimension->getRatio()));
        } elseif (self::MODE_MAX_WIDTH === $this->mode || self::MODE_INSET === $this->mode) {
            $this->dimension->setHeight((int) floor($this->dimension->getWidth() / $sourceDimension->getRatio()));
        }

        foreach ($this->parent() as $filter) {
            if ($filter instanceof RotationFilter && RotationFilter::ROTATION_180 != $filter->getRotation()) {
                $width = $this->dimension->getWidth();
                $this->dimension->setWidth($this->dimension->getHeight());
                $this->dimension->setHeight($width);
            }
        }

        return $this->dimension;
    }
}
