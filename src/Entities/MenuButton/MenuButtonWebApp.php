<?php

namespace PhpTelegramBot\Core\Entities\MenuButton;

use PhpTelegramBot\Core\Entities\WebAppInfo;

/**
 * @method string     getText()   Text on the button
 * @method WebAppInfo getWebApp() Description of the Web App that will be launched when the user presses the button. The Web App will be able to send an arbitrary message on behalf of the user using the method answerWebAppQuery.
 */
class MenuButtonWebApp extends MenuButton
{
    protected static function subEntities(): array
    {
        return [
            'web_app' => WebAppInfo::class,
        ];
    }

    protected static function presetData(): array
    {
        return [
            'type' => self::TYPE_WEB_APP,
        ];
    }
}
