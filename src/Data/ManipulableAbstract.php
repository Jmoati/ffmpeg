<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

abstract class ManipulableAbstract
{
    /** @var FilterCollection */
    protected $filters;

    /** @var Media */
    protected $media;

    /**
     * ManipulableAbstract constructor.
     */
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
     * @return ManipulableAbstract
     */
    public function setMedia(Media $media): ManipulableAbstract
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
