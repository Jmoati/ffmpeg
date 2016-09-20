<?php

namespace Jmoati\FFMpeg\Data;

class StreamCollection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var Stream[]
     */
    protected $streams = [];

    /**
     * @param array $streams
     */
    public function __construct(array $streams = [])
    {
        foreach ($streams as $stream) {
            if ($stream instanceof Stream) {
                $this->add($stream);
            } else {
                $this->streams[] = new Stream($stream);
            }
        }
    }

    /**
     * @return Stream|bool
     */
    public function first()
    {
        return reset($this->streams);
    }

    /**
     * @param Stream $stream
     *
     * @return StreamCollection
     */
    public function add(Stream $stream) : StreamCollection
    {
        $newStream = clone $stream;
        $this->streams[] = $newStream;

        return $this;
    }

    /**
     * @param Stream $stream
     *
     * @return StreamCollection
     */
    public function remove(Stream $stream) : StreamCollection
    {
        for ($i = 0, $l = count($this->streams); $i < $l; ++$i) {
            if ($this->streams[$i] === $stream) {
                unset($this->streams[$i]);
                break;
            }
        }

        return $this;
    }

    /**
     * @return StreamCollection
     */
    public function videos() : StreamCollection
    {
        return new static(array_filter(
            $this->streams,
            function (Stream $stream) {
                return $stream->isVideo();
            }
        ));
    }

    /**
     * @return StreamCollection
     */
    public function audios() : StreamCollection
    {
        return new static(array_filter(
            $this->streams,
            function (Stream $stream) {
                return $stream->isAudio();
            }
        ));
    }

    /**
     * @return StreamCollection
     */
    public function data() : StreamCollection
    {
        return new static(array_filter(
            $this->streams,
            function (Stream $stream) {
                return $stream->isData();
            }
        ));
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->streams);
    }

    /**
     * @return Stream[]
     */
    public function all() : array
    {
        return $this->streams;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->streams);
    }

    /**
     * @param string|int $offset
     *
     * @return bool
     */
    public function offsetExists($offset) : bool
    {
        return isset($this->streams[$offset]);
    }

    /**
     * @param string|int $offset
     *
     * @return Stream
     */
    public function offsetGet($offset) : Stream
    {
        return $this->streams[$offset];
    }

    /**
     * @param string|int $offset
     * @param Stream     $value
     *
     * @return StreamCollection
     */
    public function offsetSet($offset, $value) : StreamCollection
    {
        $this->streams[$offset] = $value;

        return $this;
    }

    /**
     * @param string|int $offset
     *
     * @return StreamCollection
     */
    public function offsetUnset($offset) : StreamCollection
    {
        unset($this->streams[$offset]);

        return $this;
    }

    /**
     * @param Media $media
     *
     * @return StreamCollection
     */
    public function setMedia(Media $media) : StreamCollection
    {
        foreach ($this->streams as $stream) {
            $stream->setMedia($media);
        }

        return $this;
    }
}
