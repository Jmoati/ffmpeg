<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

/**
 * @implements \IteratorAggregate<int, Stream>
 * @implements \ArrayAccess<int, Stream>
 */
final class StreamCollection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /** @var array<int, Stream> */
    private array $streams = [];

    /** @param iterable<array<string, mixed>|Stream> $streams */
    public function __construct(iterable $streams = [])
    {
        foreach ($streams as $stream) {
            if ($stream instanceof Stream) {
                $this->add($stream);
            } else {
                $this->streams[] = new Stream($stream);
            }
        }
    }

    public function first(): false|Stream
    {
        return reset($this->streams);
    }

    public function add(Stream $stream): self
    {
        $this->streams[] = clone $stream;

        return $this;
    }

    public function remove(Stream $stream): self
    {
        foreach ($this->streams as $i => $s) {
            if ($s === $stream) {
                unset($this->streams[$i]);
                break;
            }
        }

        return $this;
    }

    public function videos(): self
    {
        return new self(array_filter($this->streams, static fn (Stream $stream) => $stream->isVideo()));
    }

    public function audios(): self
    {
        return new self(array_filter($this->streams, static fn (Stream $stream) => $stream->isAudio()));
    }

    public function data(): self
    {
        return new self(array_filter($this->streams, static fn (Stream $stream) => $stream->isData()));
    }

    public function count(): int
    {
        return count($this->streams);
    }

    /** @return array<int, Stream> */
    public function all(): array
    {
        return $this->streams;
    }

    /** @return \ArrayIterator<int, Stream> */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->streams);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->streams[(int) $offset]);
    }

    public function offsetGet(mixed $offset): Stream
    {
        return $this->streams[(int) $offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (null === $offset) {
            $this->streams[] = $value;
        } else {
            $this->streams[(int) $offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->streams[(int) $offset]);
    }

    public function setMedia(Media $media): self
    {
        foreach ($this->streams as $stream) {
            $stream->setMedia($media);
        }

        return $this;
    }
}
