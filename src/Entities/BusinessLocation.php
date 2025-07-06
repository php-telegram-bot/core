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
 * Class BusinessLocation
 *
 * Contains information about the location of a business.
 *
 * @link https://core.telegram.org/bots/api#businesslocation
 *
 * @method string   getAddress()  Address of the business
 * @method Location getLocation() Optional. Location of the business
 */
class BusinessLocation extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'location' => Location::class,
        ];
    }
}
