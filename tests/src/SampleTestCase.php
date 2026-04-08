<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test;

use PHPUnit\Framework\TestCase;

abstract class SampleTestCase extends TestCase
{
    protected string $filenameImage;
    protected string $filenameAudio;
    protected string $filenameVideo;
    protected string $filenameVideoRotate;
    protected string $filenameDestination = '/tmp/destination.mov';
    protected string $filenameFrameDestination = '/tmp/destination.jpg';
    protected string $filenameHttps = 'https://symfony.com/images/logos/header-logo.svg';
    protected string $filenameBad;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filenameVideo = (string) realpath(__DIR__.'/../sample/ED.mov');
        $this->filenameAudio = (string) realpath(__DIR__.'/../sample/Jens_East_-_Daybreak_feat_Henk_sample.mp3');
        $this->filenameVideoRotate = (string) realpath(__DIR__.'/../sample/IMG_4279.MOV');
        $this->filenameImage = (string) realpath(__DIR__.'/../sample/sea-2361247_640.jpg');
        $this->filenameBad = (string) realpath(__DIR__.'/../sample/bad.mov');
    }
}
