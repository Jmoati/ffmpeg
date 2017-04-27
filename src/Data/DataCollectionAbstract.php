<?php

namespace Jmoati\FFMpeg\Data;

abstract class DataCollectionAbstract extends ManipulableAbstract implements \Countable
{
    /**
     * @var string[]
     */
    protected $properties;

    /**
     * @param string[] $properties
     */
    public function __construct(array $properties = [])
    {
        $this->properties = $properties;

        parent::__construct();
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    public function has(string $property) : bool
    {
        return isset($this->properties[$property]);
    }

    /**
     * @param string $property
     *
     * @return string|null
     */
    public function get(string $property) : ?string
    {
        if (!isset($this->properties[$property])) {
            return null;
        }

        return $this->properties[$property];
    }

    /**
     * @return int[]
     */
    public function keys() : array
    {
        return array_keys($this->properties);
    }

    /**
     * @return string[]
     */
    public function all() : array
    {
        return $this->properties;
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->properties);
    }
}
