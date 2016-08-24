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

class InlineQueryResultCachedAudio extends InlineQueryResult
{
    /**
     * @var mixed|null
     */
    protected $audio_file_id;

    /**
     * InlineQueryResultCachedAudio constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'audio';

        $this->audio_file_id = isset($data['audio_file_id']) ? $data['audio_file_id'] : null;
        if (empty($this->audio_file_id)) {
            throw new TelegramException('audio_file_id is empty!');
        }
    }

    /**
     * Get audio file id
     *
     * @return mixed|null
     */
    public function getAudioFileId()
    {
        return $this->audio_file_id;
    }
}
