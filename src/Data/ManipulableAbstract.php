<?php

namespace Jmoati\FFMpeg\Data;

abstract class ManipulableAbstract
{
    /**
     * @var FilterCollection
     */
    protected $filters;

    /**
     * @var Media
     */
    protected $media;

    public function __construct()
    {
        $this->filters = new FilterCollection($this);
    }

    /**
     * @return FilterCollection
     */
    public function filters()
    {
        return $this->filters;
    }

    /**
     * @param Media $media
     *
     * @return $this
     */
    public function setMedia(Media $media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return Media
     */
    public function media()
    {
        return $this->media;
    }
}
