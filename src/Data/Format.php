<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

class Format extends AbstractDataCollection
{
    public function getFilename(): string
    {
        return (string) $this->get('filename');
    }

    public function getDuration(): float
    {
        return (float) $this->get('duration');
    }
}
