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

class InlineQueryResultAudio extends InlineQueryResult
{
    /**
     * @var mixed|null
     */
    protected $audio_url;

    /**
     * @var mixed|null
     */
    protected $title;

    /**
     * @var mixed|null
     */
    protected $performer;

    /**
     * @var mixed|null
     */
    protected $audio_duration;

    /**
     * InlineQueryResultAudio constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'audio';

        $this->audio_url = isset($data['audio_url']) ? $data['audio_url'] : null;
        if (empty($this->audio_url)) {
            throw new TelegramException('audio_url is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        if (empty($this->title)) {
            throw new TelegramException('title is empty!');
        }

        $this->performer      = isset($data['performer']) ? $data['performer'] : null;
        $this->audio_duration = isset($data['audio_duration']) ? $data['audio_duration'] : null;
    }

    /**
     * Get audio url
     *
     * @return mixed|null
     */
    public function getAudioUrl()
    {
        return $this->audio_url;
    }

    /**
     * Get title
     *
     * @return mixed|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get performer
     *
     * @return mixed|null
     */
    public function getPerformer()
    {
        return $this->performer;
    }

    /**
     * Get audio duration
     *
     * @return mixed|null
     */
    public function getAudioDuration()
    {
        return $this->audio_duration;
    }
}
