<?php

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\FilterCollection;
use Jmoati\FFMpeg\Data\Media;

interface FilterInterface
{
    /**
     * @return string
     */
    public function __toString() : string;

    /**
     * @return Media
     */
    public function media() : Media;

    /**
     * @param FilterCollection $parent
     */
    public function setParent(FilterCollection $parent);

    /**
     * @return FilterCollection
     */
    public function parent() : FilterCollection;
}
