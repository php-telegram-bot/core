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
 * @method string    getStickerType()   Type of stickers in the set, currently one of “regular”, “mask”, “custom_emoji”
 * @method bool      getIsAnimated()    True, if the sticker set contains animated stickers
 * @method bool      getIsVideo()       True, if the sticker set contains video stickers
 * @method Sticker[] getStickers()      List of all set stickers
 * @method PhotoSize getThumb()         Optional. Sticker set thumbnail in the .WEBP or .TGS format
 */
class StickerSet extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'stickers' => [Sticker::class],
            'thumb'    => PhotoSize::class,
        ];
    }
}
