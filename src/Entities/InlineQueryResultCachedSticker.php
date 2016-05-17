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

class InlineQueryResultCachedSticker extends InlineQueryResult
{
    protected $sticker_file_id;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'sticker';

        $this->photo_file_id = isset($data['sticker_file_id']) ? $data['sticker_file_id'] : null;
        if (empty($this->sticker_file_id)) {
            throw new TelegramException('sticker_file_id is empty!');
        }
    }

    public function getStickerFileId()
    {
        return $this->sticker_file_id;
    }
}
