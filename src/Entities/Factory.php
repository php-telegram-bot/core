<?php

namespace PhpTelegramBot\Core\Entities;

interface Factory
{
    public static function make(array $data): static;
}
