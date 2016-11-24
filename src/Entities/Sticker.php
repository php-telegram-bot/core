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

/**
 * Class Sticker
 *
 * @link https://core.telegram.org/bots/api#sticker
 *
 * @method string    getFileId()   Unique identifier for this file
 * @method int       getWidth()    Sticker width
 * @method int       getHeight()   Sticker height
 * @method PhotoSize getThumb()    Optional. Sticker thumbnail in .webp or .jpg format
 * @method string    getEmoji()    Optional. Emoji associated with the sticker
 * @method int       getFileSize() Optional. File size
 */
class Sticker extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'thumb' => PhotoSize::class,
        ];
    }
}
