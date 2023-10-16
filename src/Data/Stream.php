<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

final class Stream extends AbstractDataCollection
{
    public function __construct(array $properties)
    {
        $rotation = $this->getRotation($properties);

        if ($rotation && in_array(abs($rotation), [90, 270])) {
            $width = $properties['width'];
            $height = $properties['height'];

            $properties['height'] = $width;
            $properties['width'] = $height;
            $properties['coded_height'] = $width;
            $properties['coded_width'] = $height;
        }

        parent::__construct($properties);
    }

    public function getRotation($properties): ?int
    {

        if (
            array_key_exists('tags', $properties)
            && array_key_exists('rotate',$properties['tags'])
        ) {
            return $properties['tags']['rotate'];
        }

        if (array_key_exists('side_data_list', $properties)) {
            foreach ($properties['side_data_list'] as $sideData)
            {
                if (array_key_exists('rotation', $sideData)) {
                    return  $sideData['rotation'];
                }
            }
        }

        return null;
    }

    public function isAudio(): bool
    {
        return $this->has('codec_type') && 'audio' === $this->get('codec_type');
    }

    public function isVideo(): bool
    {
        return $this->has('codec_type') && 'video' === $this->get('codec_type');
    }

    public function isData(): bool
    {
        return $this->has('codec_type') && 'data' === $this->get('codec_type');
    }

    public function isImage(): bool
    {
        if (null === $this->media()) {
            return false;
        }

        return 'image2' == $this->media()->format()->get('format_name');
    }
}
