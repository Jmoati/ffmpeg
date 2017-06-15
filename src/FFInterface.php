<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg;

use Symfony\Component\Process\Process;

interface FFInterface
{
    /**
     * @param string     $command
     * @param mixed|null $callback
     *
     * @return Process
     */
    public function run(string $command, $callback = null): Process;
}
