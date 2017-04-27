<?php

namespace Jmoati\FFMpeg\Test\Data;

use Jmoati\FFMpeg\Data\Output;
use PHPUnit\Framework\TestCase;

class OutputTest extends TestCase
{
    public function test()
    {
        $output = Output::create()
            ->setAudioCodec('aac')
            ->setAudioKiloBitrate(128)
            ->setFormat('mov')
            ->setSize('640x480')
            ->setVideoCodec('x264')
            ->setAudioRate('44k')
            ->setFrameRate(24)
            ->setExtraParams(['t' => 'value'])
            ->addExtraParam('test', 'value')
            ->addMap('v[0]');

        $params = $output->getParams();

        $this->assertEquals('aac', $params['acodec']);
        $this->assertEquals('128K', $params['b:a']);
        $this->assertEquals('mov', $params['f']);
        $this->assertEquals('x264', $params['vcodec']);
        $this->assertEquals('44k', $params['ar']);
        $this->assertEquals(24, $params['r']);
        $this->assertEquals(1, count($params['maps']));
        $this->assertTrue(isset($params['test']));
        $this->assertTrue(isset($params['t']));

        $output->clearMaps();
        $params = $output->getParams();

        $this->assertEquals(0, count($params['maps']));
        $this->assertEquals(2, count($output->getExtraParams()));
    }
}
