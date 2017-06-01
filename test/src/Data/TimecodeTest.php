<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test\Data;

use Jmoati\FFMpeg\Data\Timecode;
use PHPUnit\Framework\TestCase;

class TimecodeTest extends TestCase
{
    public function test()
    {
        $timecode = Timecode::createFromFrame(48, 24);
        $this->assertEquals(2, $timecode->getSeconds());

        $timecode->add(Timecode::createFromSeconds(5));
        $this->assertEquals(7, $timecode->getSeconds());

        $timecode->subtract(Timecode::createFromString('00:00:02.00'));
        $this->assertEquals(5, $timecode->getSeconds());

    }
}
