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
 * Class LoginUrl
 *
 * This object represents a parameter of the inline keyboard button used to automatically authorize a user.
 *
 * @link https://core.telegram.org/bots/api#loginurl
 *
 * @method string getUrl()                An HTTP URL to be opened with user authorization data added to the query string when the button is pressed. If the user refuses to provide authorization data, the original URL without information about the user will be opened. The data added is the same as described in Receiving authorization data.
 * @method string getForwardText()        Optional. New text of the button in forwarded messages.
 * @method string getBotUsername()        Optional. Username of a bot, which will be used for user authorization. See Setting up a bot for more details. If not specified, the current bot's username will be assumed. The url's domain must be the same as the domain linked with the bot. See Linking your domain to the bot for more details.
 * @method bool   getRequestWriteAccess() Optional. Pass True to request the permission for your bot to send messages to the user.
 */
class LoginUrl extends Entity
{

}
