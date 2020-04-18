<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test;

use Jmoati\FFMpeg\Data\Format;
use Jmoati\FFMpeg\Data\Media;
use Jmoati\FFMpeg\Data\Stream;
use Jmoati\FFMpeg\Data\StreamCollection;
use Jmoati\FFMpeg\FFProbe;

class FFProbeTest extends SampleTestCase
{
    /** @test */
    public function format()
    {
        $format = FFProbe::create()->format($this->filenameVideo);

        $this->assertTrue($format instanceof Format);
        $this->assertEquals($this->filenameVideo, $format->getFilename());
        $this->assertEquals(2, $format->get('nb_streams'));
        $this->assertTrue(is_numeric($format->getDuration()));
    }

    /** @test */
    public function streams()
    {
        $streams = FFProbe::create()->streams($this->filenameVideo);

        $this->assertTrue($streams instanceof StreamCollection);
        $this->assertEquals(2, $streams->count());

        $audio = $streams->audios()->first();
        $video = $streams->videos()->first();

        if (false === $audio || false === $video) {
            throw new \LogicException();
        }

        $this->assertTrue($audio->isAudio());
        $this->assertTrue($video->isVideo());

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

    /** @test */
    public function media()
    {
        $media = FFProbe::create()->media($this->filenameVideo);

        $this->assertTrue($media instanceof Media);
        $this->assertTrue($media->streams() instanceof StreamCollection);
        $this->assertTrue($media->format() instanceof Format);
    }

    /** @test */
    public function badFile()
    {
        $this->expectExceptionMessage('File can\'t be probe.');
        FFProbe::create()->format($this->filenameBad);
    }
}
