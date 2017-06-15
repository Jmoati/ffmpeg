<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\Dimension;

class ResizeFilter extends FilterAbstract implements FormatFilterInterface, FrameFilterInterface
{
    public const MODE_FORCE = 0;
    public const MODE_INSET = 1;
    public const MODE_MAX_WIDTH = 2;
    public const MODE_MAX_HEIGHT = 4;

    /** @var Dimension */
    protected $dimension;

    /** @var int */
    protected $mode;

    /**
     * @param Dimension $dimension
     * @param int       $mode
     */
    public function __construct(Dimension $dimension, int $mode = self::MODE_INSET)
    {
        $this->dimension = $dimension;
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('-s %s', (string) $this->compute());
    }

    /**
     * @return Dimension
     */
    protected function compute(): Dimension
    {
        $source = $this->media()->streams()->videos()->first();
        $sourceDimension = new Dimension((int) $source->get('width'), (int) $source->get('height'));

        if (self::MODE_MAX_HEIGHT == $this->mode || (self::MODE_INSET == $this->mode && $this->dimension->getRatio() > $sourceDimension->getRatio())) {
            $this->dimension->setWidth($this->dimension->getHeight() * $sourceDimension->getRatio());
        } elseif (self::MODE_MAX_WIDTH == $this->mode || self::MODE_INSET == $this->mode) {
            $this->dimension->setHeight($this->dimension->getWidth() / $sourceDimension->getRatio());
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
