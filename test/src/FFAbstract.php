<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test;

use PHPUnit\Framework\TestCase;

abstract class FFAbstract extends TestCase
{
    /** @var string */
    protected $filenameAudio;

    /** @var string */
    protected $filenameVideo;

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

        parent::__construct();
    }
}
