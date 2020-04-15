<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

final class Output
{
    private ?int $audioKiloBitrate = null;
    private ?string $audioCodec = null;
    private ?string $audioRate = null;
    private ?int $frameRate = null;
    private ?int $videoKiloBitrate = null;
    private ?string $videoCodec = null;
    private ?string $format = null;
    private int $passes = 1;
    private array $extraParams = [];
    private ?int $width = null;
    private ?int $height = null;
    private array $maps = [];
    private bool $upscale = false;

    public static function create(): self
    {
        return new static();
    }

    public function getAudioCodec(): ?string
    {
        return $this->audioCodec;
    }

    public function setAudioCodec(string $audioCodec): self
    {
        $this->audioCodec = $audioCodec;

        return $this;
    }

    public function getAudioKiloBitrate(): ?int
    {
        return $this->audioKiloBitrate;
    }

    public function setAudioKiloBitrate(int $audioKiloBitrate): self
    {
        $this->audioKiloBitrate = $audioKiloBitrate;

        return $this;
    }

    /**
     * @param string|int|null $value
     */
    public function addExtraParam(string $param, $value = null): self
    {
        $this->extraParams[$param] = $value;

        return $this;
    }

    public function getExtraParams(): array
    {
        return $this->extraParams;
    }

    public function setExtraParams(array $extraParams): self
    {
        $this->extraParams = $extraParams;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getPasses(): int
    {
        return $this->passes;
    }

    public function setPasses(int $passes): self
    {
        $this->passes = $passes;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width - ($width % 2);

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height - ($height % 2);

        return $this;
    }

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

    public function getUpscale(): bool
    {
        return $this->upscale;
    }

    public function setUpscale(bool $upscale): self
    {
        $this->upscale = $upscale;

        return $this;
    }

    public function getVideoCodec(): ?string
    {
        return $this->videoCodec;
    }

    public function setVideoCodec(string $videoCodec): self
    {
        $this->videoCodec = $videoCodec;

        return $this;
    }

    public function getAudioRate(): ?string
    {
        return $this->audioRate;
    }

    public function setAudioRate(string $audioRate): self
    {
        $this->audioRate = $audioRate;

        return $this;
    }

    public function getFrameRate(): ?int
    {
        return $this->frameRate;
    }

    public function setFrameRate(int $frameRate): self
    {
        $this->frameRate = $frameRate;

        return $this;
    }

    public function getVideoKiloBitrate(): ?int
    {
        return $this->videoKiloBitrate;
    }

    public function setVideoKiloBitrate(int $videoKiloBitrate): self
    {
        $this->videoKiloBitrate = $videoKiloBitrate;

        return $this;
    }

    public function clearMaps(): self
    {
        $this->maps = [];

        return $this;
    }

    public function addMap(string $map): self
    {
        $this->maps[] = $map;

        return $this;
    }

    public function getMaps(): array
    {
        return $this->maps;
    }

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

    private function setParam(array &$params, string $key, string $getter, string $suffix = ''): self
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
