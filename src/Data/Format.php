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
     */
    public function getFilename(): string
    {
        return (string) $this->get('filename');
    }

    /**
     * Returns the duration (in seconds).
     */
    public function getDuration(): float
    {
        return (float) $this->get('duration');
    }
}
