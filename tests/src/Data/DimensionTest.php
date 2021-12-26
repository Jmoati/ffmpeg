<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test\Data;

use Jmoati\FFMpeg\Data\Dimension;
use PHPUnit\Framework\TestCase;

class DimensionTest extends TestCase
{
    public function test()
    {
        $dimension = Dimension::createFromString('640x480');

        $this->assertEquals(640, $dimension->getWidth());
        $this->assertEquals(480, $dimension->getHeight());
    }
}
