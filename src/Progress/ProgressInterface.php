<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg\Progress;

interface ProgressInterface
{
    /**
     * @param $type
     * @param string $data
     *
     * @return void
     */
    public function callback($type, string $data): void;
}
