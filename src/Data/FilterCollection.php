<?php
namespace Jmoati\FFMpeg\Data;

use Jmoati\FFMpeg\Filter\FilterInterface;

class FilterCollection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var FilterInterface[]
     */
    protected $filters = [];
    /**
     * @var ManipulableAbstract
     */
    protected $parent;
    /**
     * FilterCollection constructor.
     *
     * @param ManipulableAbstract $parent
     */
    public function __construct(ManipulableAbstract $parent)
    {
        $this->parent = $parent;
    }
    /**
     * @return ManipulableAbstract
     */
    public function parent() : ManipulableAbstract
    {
        return $this->parent;
    }
    /**
     * @param FilterInterface $filter
     *
     * @return FilterCollection
     */
    public function add(FilterInterface $filter) : FilterCollection
    {
        $newFilter = clone $filter;
        $newFilter->setParent($this);
        $this->filters[] = $newFilter;
        return $this;
    }
    /**
     * @return FilterCollection
     */
    public function clear() : FilterCollection
    {
        $this->filters = [];
        return $this;
    }
    /**
     * @return string
     */
    public function __toString() : string
    {
        $result = [];
        foreach ($this->filters as $filter) {
            $result[] = (string) $filter;
        }
        return implode(' ', $result);
    }
    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->filters);
    }
    /**
     * @return Stream[]
     */
    public function all() : array
    {
        return $this->filters;
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->filters);
    }
    /**
     * @param int|string $offset
     *
     * @return bool
     */
    public function offsetExists($offset) : bool
    {
        return isset($this->filters[$offset]);
    }
    /**
     * @param int|string $offset
     *
     * @return FilterInterface
     */
    public function offsetGet($offset) : FilterInterface
    {
        return $this->filters[$offset];
    }
    /**
     * @param int|string $offset
     * @param string     $value
     *
     * @return FilterCollection
     */
    public function offsetSet($offset, $value) : FilterCollection
    {
        $this->filters[$offset] = $value;
        return $this;
    }
    /**
     * @param int|string $offset
     *
     * @return FilterCollection
     */
    public function offsetUnset($offset) : FilterCollection
    {
        unset($this->filters[$offset]);
        return $this;
    }
}
