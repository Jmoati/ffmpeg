<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

class Stream extends AbstractDataCollection
{
    public function __construct(array $properties)
    {
        if (isset($properties['tags']) && isset($properties['tags']['rotate'])) {
            if (90 == $properties['tags']['rotate'] || -90 == $properties['tags']['rotate'] || 270 == $properties['tags']['rotate']) {
                $width = $properties['width'];
                $height = $properties['height'];

                $properties['height'] = $width;
                $properties['width'] = $height;
                $properties['coded_height'] = $width;
                $properties['coded_width'] = $height;
            }
        }

        parent::__construct($properties);
    }

    public function isAudio(): bool
    {
        return $this->has('codec_type') ? 'audio' === $this->get('codec_type') : false;
    }

    public function isVideo(): bool
    {
        return $this->has('codec_type') ? 'video' === $this->get('codec_type') : false;
    }

    public function isData(): bool
    {
        return $this->has('codec_type') ? 'data' === $this->get('codec_type') : false;
    }

    public function isImage(): bool
    {
        if (null === $this->media()) {
            return false;
        }

        return 'image2' == $this->media()->format()->get('format_name');
    }
}
