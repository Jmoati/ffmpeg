<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test\Progress;

use Jmoati\FFMpeg\Progress\ProgressInterface;

class Progress implements ProgressInterface
{
    /** @var int */
    public $totalPasses;

    /** @var int */
    public $currentPass;

    /** @var int */
    public $fps;

    /** @var int */
    public $currentFrame;

    /** @var int */
    public $totalFrames;

    /** @var string */
    public $buffer;

    /** @var int */
    public $remaining = -1;

    /** @var int */
    public $pourcent = 0;

    /**
     * @param string $type
     * @param string $data
     */
    public function __invoke(string $type, string $data): void
    {
        if (preg_match('/frame=\s*([0-9]+)\s*fps=\s*([0-9]+)\s*/', $data, $matches)) {
            $this->currentFrame = (int) $matches[1];
            $this->fps = (int) $matches[2];
        }

        $this->buffer .= $data;
    }

    /**
     * @return int
     */
    public function remaining(): int
    {
        if ($this->totalFrames > 0 && $this->currentFrame > 0 && $this->fps > 0) {
            $remainingFrames = $this->totalFrames * ($this->totalPasses - $this->currentPass) + $this->totalFrames - $this->currentFrame;
            $this->remaining = (int) round($remainingFrames / $this->fps);
        }

        return $this->remaining;
    }

    /**
     * @return int
     */
    public function pourcent(): int
    {
        if ($this->totalFrames > 0 && $this->currentFrame > 0) {
            $totalFrames = $this->totalFrames * $this->totalPasses;
            $currentFrame = $this->totalFrames * ($this->currentPass - 1) + $this->currentFrame;

            $this->pourcent = (int) round($currentFrame / $totalFrames * 100);
        }

        return $this->pourcent;
    }
}
