<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Progress;

interface ProgressInterface
{
    public function __invoke(string $type, string $data): void;

    public function setTotalPasses(int $totalPasses): void;

    public function setCurrentPass(int $currentPass): void;

    public function setCurrentFrame(int $currentFrame): void;

    public function setTotalFrames(int $totalFrames): void;
}
