<?php

namespace Longman\TelegramBot\Entities\MenuButton;

/**
 * Describes that no specific value for the menu button was set.
 */
class MenuButtonDefault extends MenuButton
{
    public function __construct(array $data)
    {
        $data['type'] = 'default';
        parent::__construct($data);
    }
}
