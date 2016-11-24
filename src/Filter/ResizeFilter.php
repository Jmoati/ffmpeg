<?php

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\Dimension;

class ResizeFilter extends FilterAbstract implements FormatFilterInterface, FrameFilterInterface
{
    const MODE_FORCE = 0;
    const MODE_INSET = 1;
    const MODE_MAX_WIDTH = 2;
    const MODE_MAX_HEIGHT = 4;

    /**
     * @var Dimension
     */
    protected $dimension;

    /**
     * @var int
     */
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
     * @return Dimension
     */
    protected function compute() : Dimension
    {
        $source = $this->media()->streams()->videos()->first();
        $source_dimension = new Dimension($source->get('width'), $source->get('height'));

        if (self::MODE_MAX_HEIGHT == $this->mode || (self::MODE_INSET == $this->mode && $this->dimension->getRatio() > $source_dimension->getRatio())) {
            $this->dimension->setWidth($this->dimension->getHeight() * $source_dimension->getRatio());
        } elseif (self::MODE_MAX_WIDTH == $this->mode || self::MODE_INSET == $this->mode) {
            $this->dimension->setHeight($this->dimension->getWidth() / $source_dimension->getRatio());
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

    /**
     * @return string
     */
    public function __toString() : string
    {
        return sprintf('-s %s', (string) $this->compute());
    }
}