<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class InlineQueryResultsButton
 *
 * This entity represents a button to be shown above inline query results. You must use exactly one of the optional fields.
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultsbutton
 *
 * @method string     getText()           Label text on the button
 * @method WebAppInfo getWebApp()         Optional. Description of the Web App that will be launched when the user presses the button. The Web App will be able to switch back to the inline mode using the method switchInlineQuery inside the Web App.
 * @method string     getStartParameter() Optional. Deep-linking parameter for the /start message sent to the bot when a user presses the button. 1-64 characters, only A-Z, a-z, 0-9, _ and - are allowed.
 */
class InlineQueryResultsButton extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'web_app' => WebAppInfo::class,
        ];
    }
}
