<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Builder;

use Jmoati\FFMpeg\Data\Media;
use Jmoati\FFMpeg\Data\Output;
use Jmoati\FFMpeg\Data\Stream;

final readonly class CommandBuilder
{
    public function __construct(
        private Media $media,
        private ?Output $output = null,
        private bool $dryRun = false,
    ) {
    }

    /** @return list<string|int> */
    public function computeInputs(): array
    {
        $result = [];
        $maps = [];
        $addDefaultMaps = null === $this->output || empty($this->output->getParams()['maps']);

        /** @var Stream $stream */
        foreach ($this->media->streams() as $index => $stream) {
            array_push($result, ...$stream->filters()->__toArray());

            if ($stream->isImage()) {
                $result[] = '-loop';
                $result[] = 1;
            }

            $result[] = '-i';
            $result[] = $stream->getString('media_filename');

            if ($addDefaultMaps) {
                $maps[] = '-map';
                $maps[] = $index.':'.$stream->getString('index');
            }
        }

        return [...$result, ...$maps];
    }

    /** @return list<string|int> */
    public function computePasses(int $i, int $total, string $tmpDir): array
    {
        return 1 === $total ? [] : ['-pass', $i + 1, '-passlogfile', $tmpDir];
    }

    /** @return list<string|int> */
    public function computeFormatFilters(): array
    {
        return $this->media->format()->filters()->__toArray();
    }

    /** @return list<string|int> */
    public function computeParams(): array
    {
        if (null === $this->output) {
            return [];
        }

        $this->adjustDimensions($this->output);

        $params = $this->output->getParams();

        if ($this->dryRun) {
            $params['acodec'] = 'copy';
            $params['vcodec'] = 'copy';
            $params['f'] = 'avi';
        }

        $result = [];

        foreach ($params as $param => $value) {
            if ('maps' === $param && is_array($value)) {
                foreach ($value as $map) {
                    $result[] = '-map';
                    $result[] = $map;
                }
            } elseif (!is_array($value)) {
                $result[] = "-$param";
                $result[] = $value;
            }
        }

        return $result;
    }

    private function adjustDimensions(Output $output): void
    {
        if (null === $output->getWidth() && null === $output->getHeight()) {
            return;
        }

        $source = $this->media->streams()->videos()->first();

        if (false === $source) {
            return;
        }

        $originalWidth = $source->getInt('width');
        $originalHeight = $source->getInt('height');
        $originalRatio = $originalWidth / $originalHeight;

        if (null === $output->getWidth()) {
            $output->setWidth((int) round($output->getHeight() * $originalRatio));
        } else {
            $output->setHeight((int) round($output->getWidth() / $originalRatio));
        }

        if ($output->getUpscale()) {
            $output->addExtraParam('sws_flags', 'neighbor');

            if ('h264' === $output->getVideoCodec()) {
                $output->addExtraParam('qp', 0);
            }
        } elseif ($output->getWidth() > $originalWidth || $output->getHeight() > $originalHeight) {
            $output->setWidth($originalWidth);
            $output->setHeight($originalHeight);
        }

        $output->addExtraParam('s', sprintf('%sx%s', $output->getWidth(), $output->getHeight()));
    }
}
