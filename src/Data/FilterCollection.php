<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

use ArrayIterator;
use Jmoati\FFMpeg\Filter\FilterInterface;

class FilterCollection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /** @var FilterInterface[] */
    protected $filters = [];

    /** @var AbstractManipulable */
    protected $parent;

    public function __construct(AbstractManipulable $parent)
    {
        $this->parent = $parent;
    }

    public function __toArray(): array
    {
        $filters = [];

        foreach ($this->filters as $filter) {
            $filters = array_merge($filters, $filter->__toArray());
        }

        return $filters;
    }

    public function parent(): AbstractManipulable
    {
        return $this->parent;
    }

    public function add(FilterInterface $filter): self
    {
        $newFilter = clone $filter;
        $newFilter->setParent($this);
        $this->filters[] = $newFilter;

        return $this;
    }

    public function clear(): self
    {
        $this->filters = [];

        return $this;
    }

    public function count(): int
    {
        return count($this->filters);
    }

    public function all(): array
    {
        return $this->filters;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->filters);
    }

    /**
     * @param int|string $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->filters[$offset]);
    }

    /**
     * @param int|string $offset
     *
     * @return FilterInterface
     */
    public function offsetGet($offset): FilterInterface
    {
        return $this->filters[$offset];
    }

    /**
     * @param int|string      $offset
     * @param FilterInterface $value
     *
     * @return FilterCollection
     */
    public function offsetSet($offset, $value): self
    {
        $this->filters[$offset] = $value;

        return $this;
    }

    /**
     * @param int|string $offset
     *
     * @return FilterCollection
     */
    public function offsetUnset($offset): self
    {
        unset($this->filters[$offset]);

        return $this;
    }
}
