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
 * Class ChatPermissions
 *
 * @link https://core.telegram.org/bots/api#chatpermissions
 *
 * @method bool getCanSendMessages()       Optional. True, if the user is allowed to send text messages, contacts, locations and venues
 * @method bool getCanSendMediaMessages()  Optional. True, if the user is allowed to send audios, documents, photos, videos, video notes and voice notes, implies can_send_messages
 * @method bool getCanSendPolls()          Optional. True, if the user is allowed to send polls, implies can_send_messages
 * @method bool getCanSendOtherMessages()  Optional. True, if the user is allowed to send animations, games, stickers and use inline bots, implies can_send_media_messages
 * @method bool getCanAddWebPagePreviews() Optional. True, if the user is allowed to add web page previews to their messages, implies can_send_media_messages
 * @method bool getCanChangeInfo()         Optional. True, if the user is allowed to change the chat title, photo and other settings. Ignored in public supergroups
 * @method bool getCanInviteUsers()        Optional. True, if the user is allowed to invite new users to the chat
 * @method bool getCanPinMessages()        Optional. True, if the user is allowed to pin messages. Ignored in public supergroups
 */
class ChatPermissions extends Entity
{

}
