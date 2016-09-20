<?php

namespace Jmoati\FFMpeg\Data;

class Output
{
    /**
     * @var int|null
     */
    protected $audioKiloBitrate;

    /**
     * @var string|null
     */
    protected $audioCodec;

    /**
     * @var string|null
     */
    protected $audioRate;

    /**
     * @var int|null
     */
    protected $frameRate;

    /**
     * @var int|null
     */
    protected $videoKiloBitrate;

    /**
     * @var string|null
     */
    protected $videoCodec;

    /**
     * @var string|null
     */
    protected $format;

    /**
     * @var int
     */
    protected $passes = 1;

    /**
     * @var array
     */
    protected $extraParams = [];

    /**
     * @var int|null
     */
    protected $width;

    /**
     * @var int|null
     */
    protected $height;

    /**
     * @var bool
     */
    protected $upscale = false;

    /**
     * @param $audioCodec
     *
     * @return Output
     */
    public function setAudioCodec($audioCodec) : Output
    {
        $this->audioCodec = $audioCodec;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAudioCodec()
    {
        return $this->audioCodec;
    }

    /**
     * @param int $audioKiloBitrate
     *
     * @return Output
     */
    public function setAudioKiloBitrate($audioKiloBitrate) : Output
    {
        $this->audioKiloBitrate = $audioKiloBitrate;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAudioKiloBitrate()
    {
        return $this->audioKiloBitrate;
    }

    /**
     * @param array $extraParams
     *
     * @return Output
     */
    public function setExtraParams(array $extraParams) : Output
    {
        $this->extraParams = $extraParams;

        return $this;
    }

    /**
     * @param string $param
     * @param mixed  $value
     *
     * @return Output
     */
    public function addExtraParam(string $param, $value = null) : Output
    {
        $this->extraParams[$param] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtraParams() : array
    {
        return $this->extraParams;
    }

    /**
     * @param string $format
     *
     * @return Output
     */
    public function setFormat(string $format) : self
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param int $passes
     *
     * @return Output
     */
    public function setPasses(int $passes) : Output
    {
        $this->passes = $passes;

        return $this;
    }

    /**
     * @return int
     */
    public function getPasses() : int
    {
        return $this->passes;
    }

    /**
     * @param int $width
     *
     * @return Output
     */
    public function setWidth(int $width) : Output
    {
        $this->width = $width - ($width % 2);

        return $this;
    }

    /**
     * @param int $height
     *
     * @return Output
     */
    public function setHeight(int $height) : Output
    {
        $this->height = $height - ($height % 2);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int|null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $size
     *
     * @return Output
     */
    public function setSize(string $size) : Output
    {
        $size = explode('x', $size);

        if ($size[0] > 0) {
            $this->width = $size[0];
        }

        if ($size[1] > 0) {
            $this->height = $size[1];
        }

        return $this;
    }

    /**
     * @param bool $upscale
     *
     * @return Output
     */
    public function setUpscale(bool $upscale) : Output
    {
        $this->upscale = $upscale;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUpscale() : bool
    {
        return $this->upscale;
    }

    /**
     * @param string $videoCodec
     *
     * @return Output
     */
    public function setVideoCodec(string $videoCodec) : Output
    {
        $this->videoCodec = $videoCodec;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVideoCodec()
    {
        return $this->videoCodec;
    }

    /**
     * @param string $audioRate
     *
     * @return Output
     */
    public function setAudioRate(string $audioRate) : Output
    {
        $this->audioRate = $audioRate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAudioRate()
    {
        return $this->audioRate;
    }

    /**
     * @param int $frameRate
     *
     * @return Output
     */
    public function setFrameRate(int $frameRate) : Output
    {
        $this->frameRate = $frameRate;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFrameRate()
    {
        return $this->frameRate;
    }

    /**
     * @param int $videoKiloBitrate
     *
     * @return Output
     */
    public function setVideoKiloBitrate(int $videoKiloBitrate) : Output
    {
        $this->videoKiloBitrate = $videoKiloBitrate;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getVideoKiloBitrate()
    {
        return $this->videoKiloBitrate;
    }

    /**
     * @return Output
     */
    public static function create() : Output
    {
        return new static();
    }

    /**
     * @param string[] $params
     * @param string   $key
     * @param string   $getter
     * @param string   $suffix
     *
     * @return Output
     */
    protected function setParam(array &$params, string $key, string $getter, string $suffix = '') : Output
    {
        if (null !== $this->$getter()) {
            $params[$key] = $this->$getter().$suffix;
        }

        return $this;
    }

    /**
     * @return string[]
     */
    public function getParams() : array
    {
        $params = $this->extraParams;

        $this
            ->setParam($params, 'acodec', 'getAudioCodec')
            ->setParam($params, 'b:a', 'getAudioKiloBitrate', 'K')
            ->setParam($params, 'f', 'getFormat')
            ->setParam($params, 'vcodec', 'getVideoCodec')
            ->setParam($params, 'b:v', 'getVideoKiloBitrate', 'K')
            ->setParam($params, 'ar', 'getAudioRate')
            ->setParam($params, 'r', 'getFrameRate');

        return $params;
    }
}
