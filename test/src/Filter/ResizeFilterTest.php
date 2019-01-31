<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test\Filter;

use Jmoati\FFMpeg\Data\Dimension;
use Jmoati\FFMpeg\FFMpeg;
use Jmoati\FFMpeg\Filter\ResizeFilter;
use Jmoati\FFMpeg\Test\SampleTestCase;

class ResizeFilterTest extends SampleTestCase
{
    public function test()
    {
        $dimension = Dimension::create(640, 480);

        $resizeFilter = new ResizeFilter($dimension, ResizeFilter::MODE_MAX_HEIGHT);
        $image = FFMpeg::openFile($this->filenameImage);
        $image->format()->filters()->add($resizeFilter);

        $this->assertEquals('-s 640x480', implode(' ', $image->format()->filters()->offsetGet(0)->__toArray()));

        $this->expectException(\LogicException::class);
        $stream = $image->streams()->videos()->first();

        if (false === $stream) {
            throw new \LogicException();
        }

        $stream->filters()->add($resizeFilter);
    }
}
