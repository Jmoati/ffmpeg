<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

/**
 * Represents information about the container format.
 */
class Format extends AbstractDataCollection
{
    /**
     * Returns the filename.
     *
     * @return string
     */
    public function getFilename(): string
    {
        return $this->get('filename');
    }

    /**
     * Returns the duration (in seconds).
     *
     * @return float The duration (in seconds)
     */
    public function getDuration(): float
    {
        return (float) ($this->get('duration'));
    }
}
