<?php

declare(strict_types=1);

namespace Jmoati\FFMpeg;

use Symfony\Component\Process\Process;

interface FFInterface
{
    public function run(array $command, callable $callback = null): Process;
}
