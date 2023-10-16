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

    public function __construct(string $name)
    {
        $this->filenameVideo = realpath(__DIR__.'/../sample/ED.mov');
        $this->filenameAudio = realpath(__DIR__.'/../sample/Jens_East_-_Daybreak_feat_Henk_sample.mp3');
        $this->filenameVideoRotate = realpath(__DIR__.'/../sample/IMG_4279.MOV');
        $this->filenameImage = realpath(__DIR__.'/../sample/sea-2361247_640.jpg');
        $this->filenameBad = realpath(__DIR__.'/../sample/bad.mov');

        parent::__construct($name);
    }
}
