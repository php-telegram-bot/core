<?php

namespace Longman\TelegramBot\Entities\InputMedia;

interface InputMedia
{
    /**
     * @return string Type of the result.
     */
    public function getType();

    /**
     * @return string File to send.
     */
    public function getMedia();

    /**
     * @param string $media File to send.
     *
     * @return string
     */
    public function setMedia($media);
}
