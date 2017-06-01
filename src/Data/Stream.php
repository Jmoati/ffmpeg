<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

class Stream extends DataCollectionAbstract
{
    /**
     * Stream constructor.
     *
     * @param \string[] $properties
     */
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


    /**
     * @return bool
     */
    public function isAudio(): bool
    {
        return $this->has('codec_type') ? 'audio' === $this->get('codec_type'): false;
    }

    /**
     * @return bool
     */
    public function isVideo(): bool
    {
        return $this->has('codec_type') ? 'video' === $this->get('codec_type'): false;
    }

    /**
     * @return bool
     */
    public function isData(): bool
    {
        return $this->has('codec_type') ? 'data' === $this->get('codec_type'): false;
    }

    /**
     * return boolean.
     */
    public function isImage(): bool
    {
        return 'image2' == $this->media()->format()->get('format_name');
    }
}
