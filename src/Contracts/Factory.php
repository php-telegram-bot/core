<?php

namespace PhpTelegramBot\Core\Contracts;

interface Factory
{
    public static function make(array $data): static;
}
