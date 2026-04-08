<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Test\Progress;

use Jmoati\FFMpeg\Progress\ProgressInterface;

class Progress implements ProgressInterface
{
    public int $totalPasses = 0;
    public int $currentPass = 0;
    public int $fps = 0;
    public int $currentFrame = 0;
    public int $totalFrames = 0;
    public string $buffer = '';
    public int $remaining = -1;
    public int $percent = 0;

    public function __invoke(string $type, string $data): void
    {
        if (preg_match('/frame=\s*([0-9]+)\s*fps=\s*([0-9]+)\s*/', $data, $matches)) {
            $this->currentFrame = (int) $matches[1];
            $this->fps = (int) $matches[2];
        }

        $this->buffer .= $data;
    }

    public function setTotalPasses(int $totalPasses): void
    {
        $this->totalPasses = $totalPasses;
    }

    public function setCurrentPass(int $currentPass): void
    {
        $this->currentPass = $currentPass;
    }

    public function setCurrentFrame(int $currentFrame): void
    {
        $this->currentFrame = $currentFrame;
    }

    public function setTotalFrames(int $totalFrames): void
    {
        $this->totalFrames = $totalFrames;
    }

    public function remaining(): int
    {
        if ($this->totalFrames > 0 && $this->currentFrame > 0 && $this->fps > 0) {
            $remainingFrames = $this->totalFrames * ($this->totalPasses - $this->currentPass) + $this->totalFrames - $this->currentFrame;
            $this->remaining = (int) round($remainingFrames / $this->fps);
        }

        return $this->remaining;
    }

    public function percent(): int
    {
        if ($this->totalFrames > 0 && $this->currentFrame > 0) {
            $totalFrames = $this->totalFrames * $this->totalPasses;
            $currentFrame = $this->totalFrames * ($this->currentPass - 1) + $this->currentFrame;
            $this->percent = (int) round($currentFrame / $totalFrames * 100);
        }

        return $this->percent;
    }
}
