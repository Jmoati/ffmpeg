<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

use Jmoati\FFMpeg\Filter\FilterInterface;

/**
 * @implements \IteratorAggregate<int, FilterInterface>
 * @implements \ArrayAccess<int, FilterInterface>
 */
class FilterCollection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /** @var array<int, FilterInterface> */
    protected array $filters = [];

    public function __construct(
        protected AbstractManipulable $parent,
    ) {
    }

    /** @return list<string|int> */
    public function __toArray(): array
    {
        $result = [];

        foreach ($this->filters as $filter) {
            array_push($result, ...$filter->__toArray());
        }

        return $result;
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

    /** @return array<int, FilterInterface> */
    public function all(): array
    {
        return $this->filters;
    }

    /** @return \ArrayIterator<int, FilterInterface> */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->filters);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->filters[(int) $offset]);
    }

    public function offsetGet(mixed $offset): FilterInterface
    {
        return $this->filters[(int) $offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (null === $offset) {
            $this->filters[] = $value;
        } else {
            $this->filters[(int) $offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->filters[(int) $offset]);
    }
}
