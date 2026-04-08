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
    /** @var array<string, string|int|null> */
    private array $extraParams = [];
    private ?int $width = null;
    private ?int $height = null;
    /** @var list<string> */
    private array $maps = [];
    private bool $upscale = false;

    public static function create(): self
    {
        return new self();
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

    public function addExtraParam(string $param, string|int|null $value = null): self
    {
        $this->extraParams[$param] = $value;

        return $this;
    }

    /** @return array<string, string|int|null> */
    public function getExtraParams(): array
    {
        return $this->extraParams;
    }

    /** @param array<string, string|int|null> $extraParams */
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
        [$w, $h] = explode('x', $size);

        if ((int) $w > 0) {
            $this->width = (int) $w;
        }

        if ((int) $h > 0) {
            $this->height = (int) $h;
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

    /** @return list<string> */
    public function getMaps(): array
    {
        return $this->maps;
    }

    /** @return array<string, string|int|list<string>> */
    public function getParams(): array
    {
        /** @var array<string, string|int> $params */
        $params = array_filter($this->extraParams, static fn (mixed $v): bool => null !== $v);

        if (null !== $this->audioCodec) {
            $params['acodec'] = $this->audioCodec;
        }

        if (null !== $this->audioKiloBitrate) {
            $params['b:a'] = $this->audioKiloBitrate.'K';
        }

        if (null !== $this->format) {
            $params['f'] = $this->format;
        }

        if (null !== $this->videoCodec) {
            $params['vcodec'] = $this->videoCodec;
        }

        if (null !== $this->videoKiloBitrate) {
            $params['b:v'] = $this->videoKiloBitrate.'K';
        }

        if (null !== $this->audioRate) {
            $params['ar'] = $this->audioRate;
        }

        if (null !== $this->frameRate) {
            $params['r'] = $this->frameRate;
        }

        $params['maps'] = $this->maps;

        return $params;
    }
}
