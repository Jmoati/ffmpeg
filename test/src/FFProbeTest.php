<?php

namespace Jmoati\FFMpeg\Test;

use Jmoati\FFMpeg\Data\Stream;
use Jmoati\FFMpeg\FFProbe;
use Jmoati\FFMpeg\Data\Format;
use Jmoati\FFMpeg\Data\StreamCollection;
use Jmoati\FFMpeg\Data\Media;

class FFProbeTest extends FFAbstract
{
    public function testFormat()
    {
        $format = FFProbe::create()->format($this->filenameVideo);

        $this->assertTrue($format instanceof Format);
        $this->assertEquals($this->filenameVideo, $format->getFilename());
        $this->assertEquals(2, $format->get('nb_streams'));
        $this->assertTrue(is_numeric($format->getDuration()));
    }

    public function testStreams()
    {
        $streams = FFProbe::create()->streams($this->filenameVideo);

        $this->assertTrue($streams instanceof StreamCollection);
        $this->assertEquals(2, $streams->count());
        $this->assertTrue($streams->audios()->first()->isAudio());
        $this->assertTrue($streams->videos()->first()->isVideo());

        $this->assertEquals(2, count($streams->all()));
        $this->assertEquals(0, count($streams->data()));
        $this->assertTrue($streams[0] instanceof Stream);

        $streams[2] = $streams[0];

        $this->assertEquals(3, $streams->count());

        $streams->remove($streams[0]);

        $this->assertEquals(2, $streams->count());

        if (isset($streams[2])) {
            unset($streams[2]);
        }

        $this->assertEquals(1, $streams->count());
    }

    public function testMedia()
    {
        $media = FFProbe::create()->media($this->filenameVideo);

        $this->assertTrue($media instanceof Media);
        $this->assertTrue($media->streams() instanceof StreamCollection);
        $this->assertTrue($media->format() instanceof Format);
    }
}
