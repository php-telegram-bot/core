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
 * Class VoiceChatParticipantsInvited
 *
 * Represents a service message about new members invited to a voice chat
 *
 * @link https://core.telegram.org/bots/api#voicechatparticipantsinvited
 *
 * @method User[]      getUsers()       Optional. New members that were invited to the voice chat
 */
class VoiceChatParticipantsInvited extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'users'    => [User::class],
        ];
    }
}
