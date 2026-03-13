<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class UniqueGiftColors
 *
 * This object contains information about the color scheme for a user's name, message replies and link previews based on a unique gift.
 *
 * @link https://core.telegram.org/bots/api#uniquegiftcolors
 *
 * @method string getModelCustomEmojiId()  Custom emoji identifier of the unique gift's model
 * @method string getSymbolCustomEmojiId() Custom emoji identifier of the unique gift's symbol
 * @method int    getLightThemeMainColor() Main color used in light themes; RGB format
 * @method int[]  getLightThemeOtherColors() List of 1-3 additional colors used in light themes; RGB format
 * @method int    getDarkThemeMainColor()  Main color used in dark themes; RGB format
 * @method int[]  getDarkThemeOtherColors() List of 1-3 additional colors used in dark themes; RGB format
 */
class UniqueGiftColors extends Entity
{
}
