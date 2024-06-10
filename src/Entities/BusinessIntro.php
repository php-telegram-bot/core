<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string|null  getTitle()   Optional. Title text of the business intro
 * @method string|null  getMessage() Optional. Message text of the business intro
 * @method Sticker|null getSticker() Optional. Sticker of the business intro
 */
class BusinessIntro extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'sticker' => Sticker::class,
        ];
    }
}
