<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class UserProfileAudios
 *
 * This object represents a list of audios added to the profile of a user.
 *
 * @link https://core.telegram.org/bots/api#userprofileaudios
 *
 * @method int   getTotalCount() Total number of audios in the user's profile
 * @method Audio[] getAudios()     List of audios in the user's profile
 */
class UserProfileAudios extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'audios' => [Audio::class],
        ];
    }
}
