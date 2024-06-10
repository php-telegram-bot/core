<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string                         getTimeZoneName() Unique name of the time zone for which the opening hours are defined
 * @method BusinessOpeningHoursInterval[] getOpeningHours() List of time intervals describing business opening hours
 */
class BusinessOpeningHours extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'opening_hours' => [BusinessOpeningHoursInterval::class],
        ];
    }
}
