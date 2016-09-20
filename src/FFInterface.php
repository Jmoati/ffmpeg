<?php

namespace Jmoati\FFMpeg;

use Symfony\Component\Process\Process;

interface FFInterface
{
    /**
     * @param string        $command
     * @param callable|null $callback
     *
     * @return Process
     */
    public function run(string $command, $callback = null) : Process;
}
