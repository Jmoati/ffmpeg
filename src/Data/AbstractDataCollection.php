<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

abstract class AbstractDataCollection extends AbstractManipulable implements \Countable
{
    protected array $properties;

    public function __construct(array $properties = [])
    {
        $this->properties = $properties;

        parent::__construct();
    }

    public function has(string $property): bool
    {
        return isset($this->properties[$property]);
    }

    /**
     * @return mixed|null
     */
    public function get(string $property)
    {
        if (!isset($this->properties[$property])) {
            return null;
        }

        return $this->properties[$property];
    }

    public function keys(): array
    {
        return array_keys($this->properties);
    }

    public function all(): array
    {
        return $this->properties;
    }

    public function count(): int
    {
        return count($this->properties);
    }
}
