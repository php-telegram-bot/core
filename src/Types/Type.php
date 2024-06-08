<?php

namespace PhpTelegramBot\Core\Types;

abstract class Type
{
    protected array $additionalFields = [];

    public function __get(string $name)
    {
        return $this->additionalFields[$name];
    }

    public function __set(string $name, $value): void
    {
        $this->additionalFields[$name] = $value;
    }
}
