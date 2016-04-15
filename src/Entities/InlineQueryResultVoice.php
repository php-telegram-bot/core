<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Exception\TelegramException;

class InlineQueryResultVoice extends InlineQueryResult
{
    protected $voice_url;
    protected $title;
    protected $voice_duration;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'voice';

        $this->voice_url = isset($data['voice_url']) ? $data['voice_url'] : null;
        if (empty($this->voice_url)) {
            throw new TelegramException('voice_url is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        if (empty($this->title)) {
            throw new TelegramException('title is empty!');
        }

        $this->voice_duration = isset($data['voice_duration']) ? $data['voice_duration'] : null;
    }

    public function getVoiceUrl()
    {
        return $this->voice_url;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getVoiceDuration()
    {
        return $this->voice_duration;
    }
}
