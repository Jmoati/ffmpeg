<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test\Data;

use Jmoati\FFMpeg\FFMpeg;
use Jmoati\FFMpeg\Test\SampleTestCase;

class FormatTest extends SampleTestCase
{
    public function test()
    {
        $media = FFMpeg::openFile($this->filenameImage);
        $format = $media->format();

        $this->assertNull($format->get('nothing'));
        $this->assertTrue(in_array('filename', $format->keys(), true));
        $this->assertEquals($format->count(), count($format->all()));
    }
}
