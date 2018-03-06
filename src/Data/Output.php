<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

class Output
{
    /** @var int|null */
    protected $audioKiloBitrate;

    /** @var string|null */
    protected $audioCodec;

    /** @var string|null */
    protected $audioRate;

    /** @var int|null */
    protected $frameRate;

    /** @var int|null */
    protected $videoKiloBitrate;

    /** @var string|null */
    protected $videoCodec;

    /** @var string|null */
    protected $format;

    /** @var int */
    protected $passes = 1;

    /** @var array */
    protected $extraParams = [];

    /** @var int|null */
    protected $width;

    /** @var int|null */
    protected $height;

    /** @var array */
    protected $maps = [];

    /** @var bool */
    protected $upscale = false;

    /**
     * @param string $audioCodec
     *
     * @return Output
     */
    public function setAudioCodec(string $audioCodec): self
    {
        $this->audioCodec = $audioCodec;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAudioCodec(): ?string
    {
        return $this->audioCodec;
    }

    /**
     * @param int $audioKiloBitrate
     *
     * @return Output
     */
    public function setAudioKiloBitrate(int $audioKiloBitrate): self
    {
        $this->audioKiloBitrate = $audioKiloBitrate;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAudioKiloBitrate(): ?int
    {
        return $this->audioKiloBitrate;
    }

    /**
     * @param array $extraParams
     *
     * @return Output
     */
    public function setExtraParams(array $extraParams): self
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
    public function addExtraParam(string $param, $value = null): self
    {
        $this->extraParams[$param] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtraParams(): array
    {
        return $this->extraParams;
    }

    /**
     * @param string $format
     *
     * @return Output
     */
    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * @param int $passes
     *
     * @return Output
     */
    public function setPasses(int $passes): self
    {
        $this->passes = $passes;

        return $this;
    }

    /**
     * @return int
     */
    public function getPasses(): int
    {
        return $this->passes;
    }

    /**
     * @param int $width
     *
     * @return Output
     */
    public function setWidth(int $width): self
    {
        $this->width = $width - ($width % 2);

        return $this;
    }

    /**
     * @param int $height
     *
     * @return Output
     */
    public function setHeight(int $height): self
    {
        $this->height = $height - ($height % 2);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param string $size
     *
     * @return Output
     */
    public function setSize(string $size): self
    {
        $size = explode('x', $size);

        if ($size[0] > 0) {
            $this->width = (int) $size[0];
        }

        if ($size[1] > 0) {
            $this->height = (int) $size[1];
        }

        return $this;
    }

    /**
     * @param bool $upscale
     *
     * @return Output
     */
    public function setUpscale(bool $upscale): self
    {
        $this->upscale = $upscale;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUpscale(): bool
    {
        return $this->upscale;
    }

    /**
     * @param string $videoCodec
     *
     * @return Output
     */
    public function setVideoCodec(string $videoCodec): self
    {
        $this->videoCodec = $videoCodec;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVideoCodec(): ?string
    {
        return $this->videoCodec;
    }

    /**
     * @param string $audioRate
     *
     * @return Output
     */
    public function setAudioRate(string $audioRate): self
    {
        $this->audioRate = $audioRate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAudioRate(): ?string
    {
        return $this->audioRate;
    }

    /**
     * @param int $frameRate
     *
     * @return Output
     */
    public function setFrameRate(int $frameRate): self
    {
        $this->frameRate = $frameRate;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFrameRate(): ?int
    {
        return $this->frameRate;
    }

    /**
     * @param int $videoKiloBitrate
     *
     * @return Output
     */
    public function setVideoKiloBitrate(int $videoKiloBitrate): self
    {
        $this->videoKiloBitrate = $videoKiloBitrate;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getVideoKiloBitrate(): ?int
    {
        return $this->videoKiloBitrate;
    }

    /**
     * @return Output
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * @return Output
     */
    public function clearMaps(): self
    {
        $this->maps = [];

        return $this;
    }

    /**
     * @param string $map
     *
     * @return Output
     */
    public function addMap(string $map): self
    {
        $this->maps[] = $map;

        return $this;
    }

    /**
     * @return array
     */
    public function getMaps(): array
    {
        return $this->maps;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        $params = $this->extraParams;

        $this
            ->setParam($params, 'acodec', 'getAudioCodec')
            ->setParam($params, 'b:a', 'getAudioKiloBitrate', 'K')
            ->setParam($params, 'f', 'getFormat')
            ->setParam($params, 'vcodec', 'getVideoCodec')
            ->setParam($params, 'b:v', 'getVideoKiloBitrate', 'K')
            ->setParam($params, 'ar', 'getAudioRate')
            ->setParam($params, 'r', 'getFrameRate')
            ->setParam($params, 'maps', 'getMaps');

        return $params;
    }

    /**
     * @param array  $params
     * @param string $key
     * @param string $getter
     * @param string $suffix
     *
     * @return Output
     */
    protected function setParam(array &$params, string $key, string $getter, string $suffix = ''): self
    {
        if (null !== $this->$getter()) {
            if (is_array($this->$getter())) {
                $params[$key] = $this->$getter();
            } else {
                $params[$key] = $this->$getter().$suffix;
            }
        }

        return $this;
    }
}
