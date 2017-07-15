<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test\Data;

use Jmoati\FFMpeg\Data\Timecode;
use Jmoati\FFMpeg\FFMpeg;
use Jmoati\FFMpeg\Filter\ClipFilter;
use Jmoati\FFMpeg\Filter\FilterInterface;
use Jmoati\FFMpeg\Test\FFAbstract;
use Jmoati\FFMpeg\Test\SampleTestCase;

class FilterCollectionTest extends SampleTestCase
{
    public function test()
    {
        $media = FFMpeg::openFile($this->filenameVideo);

        $filters = $media->format()->filters();
        $this->assertEquals(0, $filters->count());

        $filters[0] = new ClipFilter();

        $this->assertEquals(1, $filters->count());
        $this->assertEquals(true, isset($filters[0]));
        $this->assertTrue($filters[0] instanceof FilterInterface);

        unset($filters[0]);

        $this->assertEquals(0, $filters->count());
    }
}
