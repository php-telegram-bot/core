<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string    getName()        Sticker set name
 * @method string    getTitle()       Sticker set title
 * @method string    getStickerType() Type of stickers in the set, currently one of “regular”, “mask”, “custom_emoji”
 * @method Sticker[] getStickers()    List of all set stickers
 * @method PhotoSize getThumbnail()   Optional. Sticker set thumbnail in the .WEBP, .TGS, or .WEBM format
 */
class StickerSet extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'stickers'  => [Sticker::class],
            'thumbnail' => PhotoSize::class,
        ];
    }
}
