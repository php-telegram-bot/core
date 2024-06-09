<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string            getFileId()           Identifier for this file, which can be used to download or reuse the file
 * @method string            getFileUniqueId()     Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method string            getType()             Type of the sticker, currently one of “regular”, “mask”, “custom_emoji”. The type of the sticker is independent from its format, which is determined by the fields is_animated and is_video.
 * @method int               getWidth()            Sticker width
 * @method int               getHeight()           Sticker height
 * @method bool              getIsAnimated()       True, if the sticker is animated
 * @method bool              getIsVideo()          True, if the sticker is a video sticker
 * @method PhotoSize|null    getThumbnail()        Optional. Sticker thumbnail in the .WEBP or .JPG format
 * @method string|null       getEmoji()            Optional. Emoji associated with the sticker
 * @method string|null       getSetName()          Optional. Name of the sticker set to which the sticker belongs
 * @method File|null         getPremiumAnimation() Optional. For premium regular stickers, premium animation for the sticker
 * @method MaskPosition|null getMaskPosition()     Optional. For mask stickers, the position where the mask should be placed
 * @method string|null       getCustomEmojiId()    Optional. For custom emoji stickers, unique identifier of the custom emoji
 * @method true|null         getNeedsRepainting()  Optional. True, if the sticker must be repainted to a text color in messages, the color of the Telegram Premium badge in emoji status, white color on chat photos, or another appropriate color in other places
 * @method int|null          getFileSize()         Optional. File size in bytes
 */
class Sticker extends Entity
{
    public const TYPE_REGULAR = 'regular';

    public const TYPE_MASK = 'mask';

    public const TYPE_CUSTOM_EMOJI = 'custom_emoji';

    protected static function subEntities(): array
    {
        return [
            'thumbnail' => PhotoSize::class,
            'premium_animation' => File::class,
            'mask_position' => MaskPosition::class,
        ];
    }
}
