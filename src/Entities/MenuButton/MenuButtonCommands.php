<?php

namespace Longman\TelegramBot\Entities\MenuButton;

/**
 * Represents a menu button, which opens the bot's list of commands.
 */
class MenuButtonCommands extends MenuButton
{
    public function __construct(array $data)
    {
        $data['type'] = 'commands';
        parent::__construct($data);
    }
}
