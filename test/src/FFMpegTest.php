<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test;

use Jmoati\FFMpeg\Builder\CommandBuilder;
use Jmoati\FFMpeg\Data\Dimension;
use Jmoati\FFMpeg\Data\Format;
use Jmoati\FFMpeg\Data\Media;
use Jmoati\FFMpeg\Data\Output;
use Jmoati\FFMpeg\Data\StreamCollection;
use Jmoati\FFMpeg\Data\Timecode;
use Jmoati\FFMpeg\FFMpeg;
use Jmoati\FFMpeg\Filter\ClipFilter;
use Jmoati\FFMpeg\Filter\ResizeFilter;
use Jmoati\FFMpeg\Filter\RotationFilter;
use Jmoati\FFMpeg\Test\Progress\Progress;
use Symfony\Component\Filesystem\Filesystem;

class FFMpegTest extends SampleTestCase
{
    public function setUp(): void
    {
        $this->tearDown();
    }

    public function tearDown(): void
    {
        (new Filesystem())->remove($this->filenameDestination);
        (new Filesystem())->remove($this->filenameFrameDestination);
    }

    public function testOpenFile()
    {
        $media = FFMpeg::openFile($this->filenameVideo);

        $this->assertTrue($media instanceof Media);
        $this->assertTrue($media->streams() instanceof StreamCollection);
        $this->assertTrue($media->format() instanceof Format);

        $streams = $media->streams();

        $this->assertTrue($media instanceof Media);
        $this->assertTrue($media->streams() instanceof StreamCollection);
        $this->assertTrue($media->format() instanceof Format);

        $this->assertEquals(2, $streams->count());

        $audio = $streams->audios()->first();
        $video = $streams->videos()->first();

        if (false === $audio || false === $video) {
            throw new \LogicException();
        }

        $this->assertTrue($audio->isAudio());
        $this->assertTrue($video->isVideo());
        $this->assertFalse($audio->isData());
        $this->assertFalse($audio->isImage());
    }

    public function testCreateFile()
    {
        $media = FFMpeg::createFile();
        $streams = $media->streams();

        $this->assertTrue($media instanceof Media);
        $this->assertTrue($media->streams() instanceof StreamCollection);
        $this->assertTrue($media->format() instanceof Format);

        $this->assertEquals(0, $streams->count());

        $this->assertFalse($streams->audios()->first());
        $this->assertFalse($streams->videos()->first());
    }

    public function testEncodage()
    {
        $video = FFMpeg::openFile($this->filenameVideoRotate);
        $audio = FFMpeg::openFile($this->filenameAudio);

        $this->assertTrue($video instanceof Media);
        $this->assertTrue($audio instanceof Media);

        $new = FFMpeg::createFile();

        $this->assertTrue($new instanceof Media);
        $this->assertEquals(0, $new->streams()->count());

        $videoStream = $video->streams()->videos()->first();
        $audioStream = $audio->streams()->audios()->first();

        if (false === $videoStream || false === $audioStream) {
            throw new \LogicException();
        }

        $new->streams()
                ->add($videoStream)
                ->add($audioStream);

        $this->assertEquals(2, $new->streams()->count());

        $new->format()->filters()->add(new ClipFilter(Timecode::createFromSeconds(1)));

        $this->assertEquals(1, $new->format()->filters()->count());
        $this->assertEquals(1, count($new->format()->filters()->all()));

        $output = Output::create()
            ->setFormat('avi');

        $commandBuilder = new CommandBuilder($new, $output);

        $inputs = $commandBuilder->computeInputs();

        $this->assertTrue(is_numeric(mb_strpos(implode(' ', $inputs), sprintf('-i %s', $this->filenameAudio))));
        $this->assertTrue(is_numeric(mb_strpos(implode(' ', $inputs), sprintf('-i %s', $this->filenameVideoRotate))));

        $filters = $commandBuilder->computeFormatFilters();
        $this->assertTrue(is_numeric(mb_strpos(implode(' ', $filters), sprintf('-t %s', Timecode::createFromSeconds(1)))));

        $result = $new->save($this->filenameDestination, $output);
        $this->assertTrue($result);
        $this->assertTrue(file_exists($this->filenameDestination));

        $check = FFMpeg::openFile($this->filenameDestination);

        $this->assertTrue($check->format()->get('duration') > 0);
    }

    public function testFilter()
    {
        $video = FFMpeg::openFile($this->filenameVideo);

        $dimension = new Dimension(320, 240);

        $rotationFilter = new RotationFilter(RotationFilter::ROTATION_90);
        $resizeFilter = new ResizeFilter($dimension);

        $video->format()->filters()->add(new ClipFilter(Timecode::createFromSeconds(1), Timecode::createFromSeconds(5)));
        $video->format()->filters()->add($rotationFilter);
        $video->format()->filters()->add($resizeFilter);

        $videoStream = $video->streams()->videos()->first();

        if (false === $videoStream) {
            throw new \LogicException();
        }

        $output = Output::create()
            ->setVideoKiloBitrate(1200)
            ->setHeight((int) ($videoStream->get('height') * 2))
            ->setUpscale(true)
            ->setVideoCodec('h264');

        $commandBuilder = new CommandBuilder($video, $output);

        $inputs = $commandBuilder->computeInputs();
        $this->assertTrue(is_numeric(mb_strpos(implode(' ', $inputs), sprintf('-i %s', $this->filenameVideo))));

        $filters = $commandBuilder->computeFormatFilters();

        $this->assertTrue(is_numeric(mb_strpos(implode(' ', $filters), sprintf('-ss %s', Timecode::createFromSeconds(5)))));
        $this->assertTrue(is_numeric(mb_strpos(implode(' ', $filters), sprintf('-t %s', Timecode::createFromSeconds(1)))));
        $this->assertTrue(is_numeric(mb_strpos(implode(' ', $filters), 'transpose=1')));

        $result = $video->save($this->filenameDestination, $output);

        if (false === $result) {
            $result = $video->save($this->filenameDestination, $output, null);
        }

        $this->assertTrue(file_exists($this->filenameDestination));
        $this->assertTrue($result);

        $check = FFMpeg::openFile($this->filenameDestination);

        $this->assertEquals(1, floor($check->format()->get('duration')));
        $this->assertEquals(3, $video->format()->filters()->count());

        $video->format()->filters()->clear();

        $this->assertEquals(0, $video->format()->filters()->count());
    }

    public function testFrame()
    {
        $timecode = Timecode::createFromFrame(2, 24);

        $video = FFMpeg::openFile($this->filenameVideo);
        $frame = $video->frame($timecode);
        $result = $frame->save($this->filenameFrameDestination);
        $this->assertTrue($result);

        $this->assertTrue(file_exists($this->filenameFrameDestination));

        (new Filesystem())->remove($this->filenameFrameDestination);

        $result = $frame->save($this->filenameFrameDestination, true);
        $this->assertTrue($result);
        $this->assertTrue(file_exists($this->filenameFrameDestination));
    }

    public function testProgress()
    {
        $progress = new Progress();
        $video = FFMpeg::openFile($this->filenameVideo);
        $video->format()->filters()->add(new ClipFilter(Timecode::createFromSeconds(1)));

        $output = Output::create()
            ->setVideoKiloBitrate(10)
            ->setPasses(2);

        $result = $video->save($this->filenameDestination, $output, $progress);
        $this->assertTrue(file_exists($this->filenameDestination));
        $this->assertTrue($result);
    }

    public function testFail()
    {
        $media = FFMpeg::openFile($this->filenameImage);
        $output = Output::create()
            ->setHeight(1)
            ->setWidth(1);

        $this->assertFalse($media->save('/dev/null/test', $output));
    }
}
