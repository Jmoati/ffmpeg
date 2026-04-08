<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\FilterCollection;
use Jmoati\FFMpeg\Data\Media;

interface FilterInterface
{
    /** @return list<string|int> */
    public function __toArray(): array;

    public function media(): ?Media;

    public function setParent(FilterCollection $parent): static;

    public function parent(): FilterCollection;
}
