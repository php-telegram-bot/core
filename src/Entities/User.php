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
 * Class User
 *
 * @link https://core.telegram.org/bots/api#user
 *
 * @method int    getId()                      Unique identifier for this user or bot
 * @method bool   getIsBot()                   True, if this user is a bot
 * @method string getFirstName()               User's or bot’s first name
 * @method string getLastName()                Optional. User's or bot’s last name
 * @method string getUsername()                Optional. User's or bot’s username
 * @method string getLanguageCode()            Optional. IETF language tag of the user's language
 * @method bool   getIsPremium()               Optional. True, if this user is a Telegram Premium user
 * @method bool   getAddedToAttachmentMenu()   Optional. True, if this user added the bot to the attachment menu
 * @method bool   getCanJoinGroups()           Optional. True, if the bot can be invited to groups. Returned only in getMe.
 * @method bool   getCanReadAllGroupMessages() Optional. True, if privacy mode is disabled for the bot. Returned only in getMe.
 * @method bool   getSupportsInlineQueries()   Optional. True, if the bot supports inline queries. Returned only in getMe.
 */
class User extends Entity
{

}
