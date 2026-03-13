<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class WebAppUser
 *
 * This object contains information about a Web App user.
 *
 * @link https://core.telegram.org/bots/api#webappuser
 *
 * @method int    getId()              Unique identifier for this user or bot. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @method bool   getIsBot()           Optional. True, if this user is a bot. Returns “user_is_bot” error if the bot is not allowed to act on behalf of the user.
 * @method string getFirstName()       User's or bot’s first name
 * @method string getLastName()        Optional. User's or bot’s last name
 * @method string getUsername()        Optional. User's or bot’s username
 * @method string getLanguageCode()    Optional. IETF language tag of the user's language
 * @method bool   getIsPremium()       Optional. True, if this user is a Telegram Premium user
 * @method bool   getAddedToAttachmentMenu() Optional. True, if this user added the bot to the attachment menu
 * @method bool   allowsWriteToPm()    Optional. True, if the user allowed the bot to send messages to them.
 * @method string getPhotoUrl()        Optional. URL of the user’s profile photo. The photo can be in .jpg or .png format and is up to 640x640 in size.
 */
class WebAppUser extends Entity
{
}
