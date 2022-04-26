<?php

namespace Longman\TelegramBot\Entities\MenuButton;

use Longman\TelegramBot\Entities\WebAppInfo;

/**
 * Represents a menu button, which launches a Web App.
 *
 * @method string     getText()   Text on the button
 * @method WebAppInfo getWebApp() Description of the Web App that will be launched when the user presses the button. The Web App will be able to send an arbitrary message on behalf of the user using the method answerWebAppQuery.
 *
 * @method $this setText(string $text)          Text on the button
 * @method $this setWebApp(WebAppInfo $web_app) Description of the Web App that will be launched when the user presses the button. The Web App will be able to send an arbitrary message on behalf of the user using the method answerWebAppQuery.
 */
class MenuButtonWebApp extends MenuButton
{
    public function __construct(array $data)
    {
        $data['type'] = 'web_app';
        parent::__construct($data);
    }

    protected function subEntities(): array
    {
        return [
            'web_app' => WebAppInfo::class,
        ];
    }
}
