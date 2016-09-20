<?php

namespace Jmoati\FFMpeg\Data;

class Stream extends DataCollectionAbstract
{
    /**
     * @return bool
     */
    public function isAudio() : bool
    {
        return $this->has('codec_type') ? 'audio' === $this->get('codec_type') : false;
    }

    /**
     * @return bool
     */
    public function isVideo() : bool
    {
        return $this->has('codec_type') ? 'video' === $this->get('codec_type') : false;
    }

    /**
     * @return bool
     */
    public function isData() : bool
    {
        return $this->has('codec_type') ? 'data' === $this->get('codec_type') : false;
    }

    /**
     * return boolean.
     */
    public function isImage() : bool
    {
        return 'image2' == $this->media()->format()->get('format_name');
    }
}
