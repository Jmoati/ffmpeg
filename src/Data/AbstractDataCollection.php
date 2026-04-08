<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

abstract class AbstractDataCollection extends AbstractManipulable implements \Countable
{
    /** @param array<string, mixed> $properties */
    public function __construct(
        protected array $properties = [],
    ) {
        parent::__construct();
    }

    public function has(string $property): bool
    {
        return array_key_exists($property, $this->properties);
    }

    public function get(string $property): mixed
    {
        return $this->properties[$property] ?? null;
    }

    public function getInt(string $property): int
    {
        if (!array_key_exists($property, $this->properties)) {
            throw new \RuntimeException(sprintf("Property %s doesn't exist.", $property));
        }

        $value = $this->properties[$property];

        return (is_scalar($value) || null === $value) ? (int) $value : 0;
    }

    public function getString(string $property): string
    {
        if (!array_key_exists($property, $this->properties)) {
            throw new \RuntimeException(sprintf("Property %s doesn't exist.", $property));
        }

        $value = $this->properties[$property];

        return (is_scalar($value) || null === $value) ? (string) $value : '';
    }

    public function getFloat(string $property): float
    {
        if (!array_key_exists($property, $this->properties)) {
            throw new \RuntimeException(sprintf("Property %s doesn't exist.", $property));
        }

        $value = $this->properties[$property];

        return (is_scalar($value) || null === $value) ? (float) $value : 0.0;
    }

    /** @return list<string> */
    public function keys(): array
    {
        return array_keys($this->properties);
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return $this->properties;
    }

    public function count(): int
    {
        return count($this->properties);
    }
}
