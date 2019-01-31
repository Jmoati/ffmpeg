<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\FilterCollection;
use Jmoati\FFMpeg\Data\Media;

interface FilterInterface
{
    public function __toArray(): array;

    public function media(): ?Media;

    public function setParent(FilterCollection $parent): FilterAbstract;

    public function parent(): FilterCollection;
}
