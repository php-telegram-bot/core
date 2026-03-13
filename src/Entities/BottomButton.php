<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class BottomButton
 *
 * This object describes a bottom button of a chat.
 *
 * @link https://core.telegram.org/bots/api#bottombutton
 *
 * @method string    getText() Label text on the button
 * @method WebAppInfo getWebApp() Optional. Description of the Web App that will be launched when the user presses the button.
 * @method string    getIconCustomEmojiId() Optional. Unique identifier of the custom emoji shown as the button icon.
 */
class BottomButton extends Entity
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
