<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

abstract class AbstractDataCollection extends AbstractManipulable implements \Countable
{
    public function __construct(
        protected array $properties = []
    ) {
        parent::__construct();
    }

    public function has(string $property): bool
    {
        return array_key_exists($property, $this->properties);
    }

    public function get(string $property): mixed
    {
        if (!array_key_exists($property, $this->properties)) {
            return null;
        }

        return $this->properties[$property];
    }

    public function getInt(string $property): int
    {
        if (!array_key_exists($property, $this->properties)) {
            throw new \Exception(sprintf('Property %s don\'t exists.', $property));
        }

        return (int) $this->properties[$property];
    }

    public function getString(string $property): string
    {
        if (!array_key_exists($property, $this->properties)) {
            throw new \Exception(sprintf('Property %s don\'t exists.', $property));
        }

        return (string) $this->properties[$property];
    }

    public function getFloat(string $property): float
    {
        if (!array_key_exists($property, $this->properties)) {
            throw new \Exception(sprintf('Property %s don\'t exists.', $property));
        }

        return (float) $this->properties[$property];
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
