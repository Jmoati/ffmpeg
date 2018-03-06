<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

abstract class AbstractManipulable
{
    /** @var FilterCollection */
    protected $filters;

    /** @var Media */
    protected $media;

    public function __construct()
    {
        $this->filters = new FilterCollection($this);
    }

    /**
     * @return FilterCollection
     */
    public function filters(): FilterCollection
    {
        return $this->filters;
    }

    /**
     * @param Media $media
     *
     * @return AbstractManipulable
     */
    public function setMedia(Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return Media
     */
    public function media(): Media
    {
        return $this->media;
    }
}
