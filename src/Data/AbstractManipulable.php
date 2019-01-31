<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

abstract class AbstractManipulable
{
    /** @var FilterCollection */
    protected $filters;

    /** @var Media|null */
    protected $media;

    public function __construct()
    {
        $this->filters = new FilterCollection($this);
    }

    public function filters(): FilterCollection
    {
        return $this->filters;
    }

    public function setMedia(Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function media(): ?Media
    {
        return $this->media;
    }
}
