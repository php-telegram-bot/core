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
 * Class StickerSet
 *
 * @link https://core.telegram.org/bots/api#stickerset
 *
 * @method string    getName()          Sticker set name
 * @method string    getTitle()         Sticker set title
 * @method bool      getIsAnimated()    True, if the sticker set contains animated stickers
 * @method bool      getContainsMasks() True, if the sticker set contains masks
 * @method Sticker[] getStickers()      List of all set stickers
 */
class StickerSet extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'stickers' => [Sticker::class],
        ];
    }
}
