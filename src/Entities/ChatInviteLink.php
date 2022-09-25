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
 * Class ChatInviteLink
 *
 * Represents an invite link for a chat
 *
 * @link https://core.telegram.org/bots/api#chatinvitelink
 *
 * @method string  getInviteLink()              The invite link. If the link was created by another chat administrator, then the second part of the link will be replaced with “…”
 * @method User    getCreator()                 Creator of the link
 * @method bool    getCreatesJoinRequest()      True, if users joining the chat via the link need to be approved by chat administrators
 * @method bool    getIsPrimary()               True, if the link is primary
 * @method bool    getIsRevoked()               True, if the link is revoked
 * @method string  getName()                    Optional. Invite link name
 * @method int     getExpireDate()              Optional. Point in time (Unix timestamp) when the link will expire or has been expired
 * @method int     getMemberLimit()             Optional. Maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999
 * @method int     getPendingJoinRequestCount() Optional. Number of pending join requests created using this link
 */
class ChatInviteLink extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'creator' => User::class,
        ];
    }
}
