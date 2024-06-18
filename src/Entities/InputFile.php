<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Exceptions\InvalidArgumentException;

class InputFile
{
    public static function attachFile(string $field, string $filepath): array
    {
        $file_id = uniqid($field . '_');

        if (! is_file($filepath)) {
            throw new InvalidArgumentException("Cannot attach file to '$field'. $filepath must be a valid filepath.");
        }

        return [
            $field               => 'attach://' . $file_id,
            '__file_' . $file_id => fopen($filepath, 'r'),
        ];
    }
}
