<?php

namespace Jmoati\FFMpeg\Builder;

use Jmoati\FFMpeg\Data\Media;
use Jmoati\FFMpeg\Data\Output;

class CommandBuilder
{
    /**
     * @var Media
     */
    protected $media;

    /**
     * @var Output
     */
    protected $output;

    /**
     * @var string[]
     */
    protected $files = [];

    /**
     * @var string[]
     */
    protected $params = [];

    /**
     * @var bool
     */
    protected $dryRun;

    /**
     * @param Media       $media
     * @param Output|null $output
     * @param bool        $dryRun
     */
    public function __construct(Media $media, Output $output = null, $dryRun = false)
    {
        $this->media = $media;
        $this->output = $output;
        $this->dryRun = $dryRun;
    }

    /**
     * @return string
     */
    public function computeInputs() : string
    {
        $result = [];

        foreach ($this->media->streams() as $stream) {
            $result[] = (string) $stream->filters();

            if ('image2' == $stream->media()->format()->get('format_name')) {
                $result[] = '-loop 1';
            }

            $result[] = sprintf('-i "%s"', $stream->get('media_filename'));
        }

        if (!(null !== $this->output && isset($this->output->getParams()['maps']))) {
            foreach ($this->media->streams() as $index => $stream) {
                $result[] = sprintf('-map %s:%s', $index, $stream->get('index'));
            }
        }

        return implode(' ', $result);
    }

    /**
     * @param int    $i
     * @param int    $total
     * @param string $tmpDir
     *
     * @return string
     */
    public function computePasses(int $i, int $total, string $tmpDir) : string
    {
        return 1 === $total ? '' : sprintf(
            '-pass %d -passlogfile %s',
            $i + 1,
            $tmpDir
        );
    }

    /**
     * @return string
     */
    public function computeFormatFilters() : string
    {
        return (string) $this->media->format()->filters();
    }

    /**
     * @return string
     */
    public function computeParams() : string
    {
        if (null === $this->output) {
            return '';
        }

        if (null !== $this->output->getWidth() || null !== $this->output->getHeight()) {
            $originalWidth = $this->media->streams()->videos()->first()->get('width');
            $originalHeight = $this->media->streams()->videos()->first()->get('height');
            $originalRatio = $originalWidth / $originalHeight;

            if (null === $this->output->getWidth()) {
                $this->output->setWidth(round($this->output->getHeight() * $originalRatio));
            } else {
                $this->output->setHeight(round($this->output->getWidth() / $originalRatio));
            }

            if ($this->output->getUpscale()) {
                $this->output->addExtraParam('sws_flags', 'neighbor');

                if ('h264' == $this->output->getVideoCodec()) {
                    $this->output->addExtraParam('qp', 0);
                }
            } else {
                if (($this->output->getWidth() > $originalWidth) || ($this->output->getHeight() > $originalHeight)) {
                    $this->output->setWidth($originalWidth);
                    $this->output->setHeight($originalHeight);
                }
            }

            $this->output->addExtraParam('s', sprintf('%sx%s', $this->output->getWidth(), $this->output->getHeight()));
        }

        $result = [];
        $params = $this->output->getParams();

        if (true === $this->dryRun) {
            $params['acodec'] = 'copy';
            $params['vcodec'] = 'copy';
            $params['f'] = 'avi';
        }

        foreach ($params as $param => $value) {
            if ('maps' == $param) {
                foreach ($value as $map) {
                    $result[] = "-map $map";
                }
            } else {
                $result[] = "-$param $value";
            }
        }

        return implode(' ', $result);
    }
}
