<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method User getTraveler() User that triggered the alert
 * @method User getWatcher()  User that set the alert
 * @method int  getDistance() The distance between the users
 */
class ProximityAlertTriggered extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'traveler' => User::class,
            'watcher'  => User::class,
        ];
    }
}
