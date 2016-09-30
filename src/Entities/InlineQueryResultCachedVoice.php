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

class InlineQueryResultCachedVoice extends InlineQueryResult
{
    /**
     * @var mixed|null
     */
    protected $voice_file_id;

    /**
     * @var mixed|null
     */
    protected $title;

    /**
     * @var mixed|null
     */
    protected $description;

    /**
     * @var mixed|null
     */
    protected $caption;

    /**
     * InlineQueryResultCachedVoice constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'voice';

        $this->voice_file_id = isset($data['voice_file_id']) ? $data['voice_file_id'] : null;
        if (empty($this->voice_file_id)) {
            throw new TelegramException('voice_file_id is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        if (empty($this->title)) {
            throw new TelegramException('title is empty!');
        }

        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->caption     = isset($data['caption']) ? $data['caption'] : null;
    }

    /**
     * Get voice file id
     *
     * @return mixed|null
     */
    public function getVoiceFileId()
    {
        return $this->voice_file_id;
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
     * Get description
     *
     * @return mixed|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get caption
     *
     * @return mixed|null
     */
    public function getCaption()
    {
        return $this->caption;
    }
}
