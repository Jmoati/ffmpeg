<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test;

use PHPUnit\Framework\TestCase;

abstract class SampleTestCase extends TestCase
{
    /** @var string */
    protected $filenameImage;

    /** @var string */
    protected $filenameAudio;

    /** @var string */
    protected $filenameVideo;

    /** @var string */
    protected $filenameVideoRotate;

    /** @var string */
    protected $filenameDestination = '/tmp/destination.mov';

    /** @var string */
    protected $filenameFrameDestination = '/tmp/destination.jpg';

    /**
     * FFAbstract constructor.
     */
    public function __construct()
    {
        $this->filenameVideo = realpath(__DIR__.'/../sample/ED.mov');
        $this->filenameAudio = realpath(__DIR__.'/../sample/Jens_East_-_Daybreak_feat_Henk_sample.mp3');
        $this->filenameVideoRotate = realpath(__DIR__.'/../sample/IMG_4279.MOV');
        $this->filenameImage = realpath(__DIR__ . '/../sample/sea-2361247_640.jpg');

        parent::__construct();
    }
}
