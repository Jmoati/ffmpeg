<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test\Builder;

use Jmoati\FFMpeg\Builder\CommandBuilder;
use Jmoati\FFMpeg\Data\Output;
use Jmoati\FFMpeg\FFMpeg;
use Jmoati\FFMpeg\Test\SampleTestCase;

class CommandBuilderTest extends SampleTestCase
{
    public function test(): void
    {
        $media = FFMpeg::openFile($this->filenameImage);

        $builder = new CommandBuilder($media);
        $computedInputs = $builder->computeInputs();

        $this->assertTrue(false !== mb_strstr(implode(' ', $computedInputs), '-loop 1'));
        $this->assertTrue(false !== mb_strstr(implode(' ', $computedInputs), '-map 0:0'));

        $this->assertTrue('' === implode(' ', $builder->computeParams()));

        $output = Output::create()
            ->setWidth(10000);

        $builder = new CommandBuilder($media, $output);
        $stream = $media->streams()->videos()->first();

        if (false === $stream) {
            throw new \LogicException();
        }

        $this->assertTrue(false !== mb_strstr(implode(' ', $builder->computeParams()), sprintf('%sx%s', $stream->get('width'), $stream->get('height'))));
    }

    public function testTheStreamsOfAFileShareOneInput(): void
    {
        $media = FFMpeg::openFile($this->filenameVideo);

        $this->assertSame(
            ['-i', $this->filenameVideo, '-map', '0:0', '-map', '0:1'],
            (new CommandBuilder($media))->computeInputs(),
        );
    }

    public function testTheStreamsOfTwoFilesGetAnInputEach(): void
    {
        $video = FFMpeg::openFile($this->filenameVideo)->streams()->videos()->first();
        $audio = FFMpeg::openFile($this->filenameAudio)->streams()->audios()->first();

        if (false === $video || false === $audio) {
            throw new \LogicException();
        }

        $media = FFMpeg::createFile();
        $media->streams()->add($video)->add($audio);

        $this->assertSame(
            ['-i', $this->filenameVideo, '-i', $this->filenameAudio, '-map', '0:'.$video->get('index'), '-map', '1:'.$audio->get('index')],
            (new CommandBuilder($media))->computeInputs(),
        );
    }
}
