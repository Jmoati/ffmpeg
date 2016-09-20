<?php

namespace Jmoati\FFMpeg\Data;

/**
 * Represents information about the container format.
 */
class Format extends DataCollectionAbstract
{
    /**
     * Returns the filename.
     *
     * @return string
     */
    public function getFilename() : string
    {
        return $this->get('filename');
    }

    /**
     * Returns the duration (in seconds).
     *
     * @return float The duration (in seconds)
     */
    public function getDuration() : float
    {
        return floatval($this->get('duration'));
    }
}
