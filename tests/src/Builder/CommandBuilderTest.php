<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test\Builder;

use Jmoati\FFMpeg\Builder\CommandBuilder;
use Jmoati\FFMpeg\Data\Output;
use Jmoati\FFMpeg\FFMpeg;
use Jmoati\FFMpeg\Test\SampleTestCase;

class CommandBuilderTest extends SampleTestCase
{
    public function test()
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
}
