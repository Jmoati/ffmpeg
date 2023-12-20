<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

class Format extends AbstractDataCollection
{
    public function getFilename(): string
    {
        return $this->getString('filename');
    }

    public function getDuration(): float
    {
        return $this->getFloat('duration');
    }
}
