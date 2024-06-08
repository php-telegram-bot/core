<?php

namespace PhpTelegramBot\Core\Exceptions;

class NotYetImplementedException extends \BadMethodCallException
{
    public function __construct(string $message = 'Not yet implemented')
    {
        parent::__construct($message);
    }
}
