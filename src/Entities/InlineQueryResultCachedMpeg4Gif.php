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

class InlineQueryResultCachedMpeg4Gif extends InlineQueryResult
{
    protected $mpeg4_file_id;
    protected $title;
    protected $caption;

    /**
     * InlineQueryResultCachedMpeg4Gif constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'mpeg4_gif';

        $this->mpeg4_file_id = isset($data['mpeg4_file_id']) ? $data['mpeg4_file_id'] : null;
        if (empty($this->mpeg4_file_id)) {
            throw new TelegramException('mpeg4_file_id is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        $this->caption = isset($data['caption']) ? $data['caption'] : null;
    }

    public function getMpeg4FileId()
    {
        return $this->mpeg4_file_id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getCaption()
    {
        return $this->caption;
    }
}
