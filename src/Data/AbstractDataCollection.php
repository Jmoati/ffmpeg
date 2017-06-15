<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

abstract class AbstractDataCollection extends AbstractManipulable implements \Countable
{
    /** @var array */
    protected $properties;

    /**
     * @param array $properties
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
    public function has(string $property): bool
    {
        return isset($this->properties[$property]);
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function get(string $property)
    {
        if (!isset($this->properties[$property])) {
            return null;
        }

        return $this->properties[$property];
    }

    /**
     * @return int[]
     */
    public function keys(): array
    {
        return array_keys($this->properties);
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return $this->properties;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->properties);
    }
}
