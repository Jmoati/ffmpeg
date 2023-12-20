<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

use Jmoati\FFMpeg\Filter\FilterInterface;

class FilterCollection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /** @var FilterInterface[] */
    protected array $filters = [];

    public function __construct(
        protected AbstractManipulable $parent
    ) {
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

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->filters);
    }

    /**
     * @param int|string $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->filters[$offset]);
    }

    /**
     * @param int|string $offset
     */
    public function offsetGet($offset): FilterInterface
    {
        return $this->filters[$offset];
    }

    /**
     * @param int|string      $offset
     * @param FilterInterface $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->filters[$offset] = $value;
    }

    /**
     * @param int|string $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->filters[$offset]);
    }
}
