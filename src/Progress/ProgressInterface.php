<?php

namespace Jmoati\FFMpeg\Progress;

interface ProgressInterface
{
    /**
     * @param $type
     * @param string $data
     *
     * @return null
     */
    public function callback($type, string $data);
}
