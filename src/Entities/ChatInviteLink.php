<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string      getInviteLink()              The invite link. If the link was created by another chat administrator, then the second part of the link will be replaced with “…”.
 * @method User        getCreator()                 Creator of the link
 * @method bool        getCreatesJoinRequest()      True, if users joining the chat via the link need to be approved by chat administrators
 * @method bool        getIsPrimary()               True, if the link is primary
 * @method bool        getIsRevoked()               True, if the link is revoked
 * @method string|null getName()                    Optional. Invite link name
 * @method int|null    getExpireDate()              Optional. Point in time (Unix timestamp), when the link will expire or has been expired
 * @method int|null    getMemberLimit()             Optional. The maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999
 * @method int|null    getPendingJoinRequestCount() Optional. Number of pending join requests created using this link
 */
class ChatInviteLink extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'creator' => User::class,
        ];
    }
}
