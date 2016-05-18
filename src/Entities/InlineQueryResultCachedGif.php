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

class InlineQueryResultCachedGif extends InlineQueryResult
{
    protected $gif_file_id;
    protected $title;
    protected $description;
    protected $caption;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'gif';

        $this->gif_file_id = isset($data['gif_file_id']) ? $data['gif_file_id'] : null;
        if (empty($this->gif_file_id)) {
            throw new TelegramException('gif_file_id is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        $this->caption = isset($data['caption']) ? $data['caption'] : null;
    }

    public function getGifFileId()
    {
        return $this->gif_file_id;
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
