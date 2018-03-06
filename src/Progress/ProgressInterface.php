<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Progress;

interface ProgressInterface
{
    /**
     * @param string $type
     * @param string $data
     */
    public function __invoke(string $type, string $data): void;
}
