<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method InputFile|string  getSticker()      The added sticker. Pass a file_id as a String to send a file that already exists on the Telegram servers, pass an HTTP URL as a String for Telegram to get a file from the Internet, upload a new one using multipart/form-data, or pass “attach://<file_attach_name>” to upload a new one using multipart/form-data under <file_attach_name> name. Animated and video stickers can't be uploaded via HTTP URL.
 * @method string            getFormat()       Format of the added sticker, must be one of “static” for a .WEBP or .PNG image, “animated” for a .TGS animation, “video” for a WEBM video
 * @method string[]          getEmojiList()    List of 1-20 emoji associated with the sticker
 * @method MaskPosition|null getMaskPosition() Optional. Position where the mask should be placed on faces. For “mask” stickers only.
 * @method string[]|null     getKeywords()     Optional. List of 0-20 search keywords for the sticker with total length of up to 64 characters. For “regular” and “custom_emoji” stickers only.
 */
class InputSticker extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'sticker'       => InputFile::class,
            'mask_position' => MaskPosition::class,
        ];
    }
}
