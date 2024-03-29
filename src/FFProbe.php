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

    private string $bin;

    public function __construct()
    {
        $process = new Process(['which', 'ffprobe']);
        $process->run();

        if ($process->getExitCode() > 0) {
            throw new \Exception('no ffprobe binary found');
        }

        $this->bin = str_replace(\PHP_EOL, '', $process->getOutput());
    }

    public static function create(): self
    {
        return new static();
    }

    public function format(string $filename): Format
    {
        $format = $this->probe($filename, self::COMMAND_FORMAT);
        assert($format instanceof Format);

        return $format;
    }

    public function streams(string $filename): StreamCollection
    {
        $streamCollection = $this->probe($filename, self::COMMAND_STREAMS);
        assert($streamCollection instanceof StreamCollection);

        return $streamCollection;
    }

    public function media(string $filename): Media
    {
        $media = $this->probe($filename, self::COMMAND_MEDIA);
        assert($media instanceof Media);

        return $media;
    }

    public function run(array $command, callable $callback = null): Process
    {
        $process = new Process(array_merge([$this->bin], $command), null, null, null, 0.0);
        $process->run($callback);

        return $process;
    }

    /**
     * @throws \Exception
     */
    private function probe(string $filename, string $command): Format|Media|StreamCollection
    {
        $process = $this->run(array_merge(explode(' ', self::COMMAND_MEDIA), ['-print_format', 'json', $filename]));

        if ($process->run() > 1) {
            throw new \Exception('File can\'t be probe.');
        }

        $output = json_decode(mb_convert_encoding($process->getOutput(), 'UTF-8'), true);

        assert(is_array($output));

        if (empty($output)) {
            throw new \Exception('File can\'t be probe.');
        }

        assert(is_iterable($output['streams']));

        foreach ($output['streams'] as &$stream) {
            $stream['media_filename'] = $output['format']['filename'];
        }
        unset($stream);

        return match ($command) {
            self::COMMAND_STREAMS => new StreamCollection($output['streams']),
            self::COMMAND_FORMAT => new Format($output['format']),
            self::COMMAND_MEDIA => new Media(new FFMpeg($this), new StreamCollection($output['streams']), new Format($output['format'])),
            default => throw new \Exception('Command not found'),
        };
    }
}
