<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\FilterCollection;
use Jmoati\FFMpeg\Data\Format;
use Jmoati\FFMpeg\Data\Frame;
use Jmoati\FFMpeg\Data\Media;
use Jmoati\FFMpeg\Data\Stream;

abstract class FilterAbstract implements FilterInterface
{
    protected FilterCollection $parent;

    /** @return list<string|int> */
    abstract public function __toArray(): array;

    public function setParent(FilterCollection $parent): static
    {
        $owner = $parent->parent();
        $filterName = mb_substr(mb_strrchr(static::class, '\\') ?: static::class, 1);

        if ($owner instanceof Stream && !($this instanceof StreamFilterInterface)) {
            throw new \LogicException(sprintf("Filter %s can't be used with Stream.", $filterName));
        }

        if ($owner instanceof Format && !($this instanceof FormatFilterInterface)) {
            throw new \LogicException(sprintf("Filter %s can't be used with Format.", $filterName));
        }

        if ($owner instanceof Frame && !($this instanceof FrameFilterInterface)) {
            throw new \LogicException(sprintf("Filter %s can't be used with Frame.", $filterName));
        }

        $this->parent = $parent;

        return $this;
    }

    public function parent(): FilterCollection
    {
        return $this->parent;
    }

    public function media(): ?Media
    {
        return $this->parent()->parent()->media();
    }
}
