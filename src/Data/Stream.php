<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Data;

final class Stream extends AbstractDataCollection
{
    /** @param array<string, mixed> $properties */
    public function __construct(array $properties)
    {
        $rotation = $this->getRotation($properties);

        if ($rotation && in_array(abs($rotation), [90, 270], true)) {
            $width = $properties['width'];
            $height = $properties['height'];

            $properties['height'] = $width;
            $properties['width'] = $height;
            $properties['coded_height'] = $width;
            $properties['coded_width'] = $height;
        }

        parent::__construct($properties);
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

        $raw = $this->media()->format()->get('format_name');
        $formatName = (is_scalar($raw) || null === $raw) ? (string) $raw : '';

        return 'image2' === $formatName || str_ends_with($formatName, '_pipe');
    }

    /** @param array<string, mixed> $properties */
    private function getRotation(array $properties): ?int
    {
        $tags = $properties['tags'] ?? null;

        if (is_array($tags) && array_key_exists('rotate', $tags)) {
            $r = $tags['rotate'];

            return (is_scalar($r) || null === $r) ? (int) $r : null;
        }

        $sideDataList = $properties['side_data_list'] ?? null;

        if (is_array($sideDataList)) {
            foreach ($sideDataList as $sideData) {
                if (is_array($sideData) && array_key_exists('rotation', $sideData)) {
                    $r = $sideData['rotation'];

                    return (is_scalar($r) || null === $r) ? (int) $r : null;
                }
            }
        }

        return null;
    }
}
