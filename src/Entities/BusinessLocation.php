<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string        getAddress()  Address of the business
 * @method Location|null getLocation() Optional. Location of the business
 */
class BusinessLocation extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'location' => Location::class,
        ];
    }
}
