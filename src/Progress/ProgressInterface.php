<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Progress;

interface ProgressInterface
{
    /**
     * @param string $type
     * @param string $data
     * @return void
     */
    public function callback(string $type, string $data): void;
}
