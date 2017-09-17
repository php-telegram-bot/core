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
 * @method string getName()          Sticker set name
 * @method string getTitle()         Sticker set title
 * @method bool   getContainsMasks() True, if the sticker set contains masks
 */
class StickerSet extends Entity
{
    /**
     * List of all set stickers
     *
     * This method overrides the default getStickers method
     * and returns a nice array of Sticker objects.
     *
     * @return null|Sticker[]
     */
    public function getStickers()
    {
        $pretty_array = $this->makePrettyObjectArray(Sticker::class, 'stickers');

        return empty($pretty_array) ? null : $pretty_array;
    }
}
