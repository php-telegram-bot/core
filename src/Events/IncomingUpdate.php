<?php

namespace PhpTelegramBot\Core\Events;

use PhpTelegramBot\Core\Entities\Update;

class IncomingUpdate extends Event
{
    public function __construct(
        public Update $update,
    ) {
    }
}
