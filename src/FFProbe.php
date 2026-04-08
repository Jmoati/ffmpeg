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

    private readonly string $bin;

    public function __construct()
    {
        $process = new Process(['which', 'ffprobe']);
        $process->run();

        if ($process->getExitCode() > 0) {
            throw new \RuntimeException('no ffprobe binary found');
        }

        $this->bin = mb_trim($process->getOutput());
    }

    public static function create(): self
    {
        return new self();
    }

    public function format(string $filename): Format
    {
        $result = $this->probe($filename, self::COMMAND_FORMAT);

        if (!$result instanceof Format) {
            throw new \RuntimeException('Unexpected probe result type.');
        }

        return $result;
    }

    public function streams(string $filename): StreamCollection
    {
        $result = $this->probe($filename, self::COMMAND_STREAMS);

        if (!$result instanceof StreamCollection) {
            throw new \RuntimeException('Unexpected probe result type.');
        }

        return $result;
    }

    public function media(string $filename): Media
    {
        $result = $this->probe($filename, self::COMMAND_MEDIA);

        if (!$result instanceof Media) {
            throw new \RuntimeException('Unexpected probe result type.');
        }

        return $result;
    }

    /** @param list<string|int> $command */
    public function run(array $command, ?callable $callback = null): Process
    {
        $process = new Process(array_merge([$this->bin], $command), timeout: 0.0);
        $process->run($callback);

        return $process;
    }

    private function probe(string $filename, string $command): Format|Media|StreamCollection
    {
        $process = $this->run([...explode(' ', self::COMMAND_MEDIA), '-print_format', 'json', $filename]);

        if ($process->getExitCode() > 0) {
            throw new \RuntimeException("File can't be probe.");
        }

        /** @var array<string, mixed>|null $output */
        $output = json_decode(mb_convert_encoding($process->getOutput(), 'UTF-8'), true);

        if (!is_array($output) || empty($output)) {
            throw new \RuntimeException("File can't be probe.");
        }

        /** @var array<string, mixed> $format */
        $format = $output['format'] ?? [];

        /** @var list<array<string, mixed>> $streams */
        $streams = $output['streams'] ?? [];

        $mediaFilename = is_string($format['filename'] ?? null) ? $format['filename'] : '';

        foreach ($streams as &$stream) {
            $stream['media_filename'] = $mediaFilename;
        }
        unset($stream);

        return match ($command) {
            self::COMMAND_STREAMS => new StreamCollection($streams),
            self::COMMAND_FORMAT => new Format($format),
            self::COMMAND_MEDIA => new Media(new FFMpeg($this), new StreamCollection($streams), new Format($format)),
            default => throw new \RuntimeException('Command not found'),
        };
    }
}
