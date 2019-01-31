<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Progress;

interface ProgressInterface
{
    public function __invoke(string $type, string $data): void;
}
