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
     * This method overrides the default getStickers method and returns a nice array
     *
     * @return Sticker[]
     */
    public function getStickers()
    {
        $all_stickers = [];

        if ($these_stickers = $this->getProperty('stickers')) {
            foreach ($these_stickers as $stickers) {
                $new_stickers = [];
                foreach ($stickers as $sticker) {
                    $new_stickers[] = new Sticker($sticker);
                }
                $all_stickers[] = $new_stickers;
            }
        }

        return $all_stickers;
    }
}
