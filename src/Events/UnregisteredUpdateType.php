<?php

namespace PhpTelegramBot\Core\Events;

use PhpTelegramBot\Core\Entities\Update;

class UnregisteredUpdateType extends Event
{
    public function __construct(
        public Update $update,
    ) {
    }
}
