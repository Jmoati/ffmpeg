<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg;

use Jmoati\FFMpeg\Data\Format;
use Jmoati\FFMpeg\Data\Media;
use Jmoati\FFMpeg\Data\StreamCollection;
use Symfony\Component\Process\Process;

final class FFProbe implements FFInterface
{
    private const COMMAND_STREAMS = '-show_streams';
    private const COMMAND_FORMAT = '-show_format';
    private const COMMAND_MEDIA = '-show_streams -show_format';

    /** @var string */
    private $bin;

    public function __construct()
    {
        $process = new Process('which ffprobe');
        $process->run();

        if (0 === $process->getExitCode()) {
            $this->bin = 'ffprobe';
        } elseif (file_exists(__DIR__.'/../vendor/bin/ffprobe')) {
            $this->bin = realpath(__DIR__.'/../vendor/bin/ffprobe');
        } else {
            $this->bin = realpath(__DIR__.'/../../../bin/ffprobe');
        }
    }

    /**
     * @return FFProbe
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * @param string $filename
     *
     * @throws \Exception
     *
     * @return Format
     */
    public function format(string $filename): Format
    {
        $format = $this->probe($filename, self::COMMAND_FORMAT);

        if (!($format instanceof Format)) {
            throw new \LogicException();
        }

        return $format;
    }

    /**
     * @param string $filename
     *
     * @throws \Exception
     *
     * @return StreamCollection
     */
    public function streams(string $filename): StreamCollection
    {
        $streamCollection = $this->probe($filename, self::COMMAND_STREAMS);

        if (!($streamCollection instanceof StreamCollection)) {
            throw new \LogicException();
        }

        return $streamCollection;
    }

    /**
     * @param string $filename
     *
     * @throws \Exception
     *
     * @return Media
     */
    public function media(string $filename): Media
    {
        $media = $this->probe($filename, self::COMMAND_MEDIA);

        if (!($media instanceof Media)) {
            throw new \LogicException();
        }

        return $media;
    }

    /**
     * @param string        $command
     * @param callable|null $callback
     *
     * @return Process
     */
    public function run(string $command, callable $callback = null): Process
    {
        $process = new Process('nice '.$this->bin.' '.$command, null, null, null, 0.0);
        $process->run($callback);

        return $process;
    }

    /**
     * @param string $filename
     * @param string $command
     *
     * @throws \Exception
     *
     * @return Format|StreamCollection|Media
     */
    private function probe(string $filename, string $command)
    {
        $process = $this->run(sprintf('%s -print_format json "%s"', self::COMMAND_MEDIA, $filename));

        if (0 !== $process->run()) {
            throw new \Exception('File can\'t be probe.');
        }

        $output = json_decode(utf8_encode($process->getOutput()), true);
        foreach ($output['streams'] as &$stream) {
            $stream['media_filename'] = $output['format']['filename'];
        }
        unset($stream);

        switch ($command) {
            case self::COMMAND_STREAMS:
                return new StreamCollection($output['streams']);
            case self::COMMAND_FORMAT:
                return new Format($output['format']);
            case self::COMMAND_MEDIA:
                return new Media(new FFMpeg($this), new StreamCollection($output['streams']), new Format($output['format']));
            default:
                throw new \Exception('Command not found');
        }
    }
}
