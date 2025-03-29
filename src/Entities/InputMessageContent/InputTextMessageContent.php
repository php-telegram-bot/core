<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities\InputMessageContent;

use Longman\TelegramBot\Entities\InlineQuery\InlineEntity;

/**
 * Represents the content of a text message to be sent as the result of an inline query.
 *
 * @link https://core.telegram.org/bots/api#inputtextmessagecontent
 *
 * @method string             getMessageText()           Text of the message to be sent, 1-4096 characters.
 * @method string             getParseMode()             Optional. Send Markdown or HTML, if you want Telegram apps to show bold, italic, fixed-width text or inline URLs in your bot's message.
 * @method MessageEntity[]    getEntities()              Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
 * @method LinkPreviewOptions getLinkPreviewOptions()    Optional. Link preview generation options for the message
 *
 * @method $this setMessageText(string $message_text)                            Text of the message to be sent, 1-4096 characters.
 * @method $this setParseMode(string $parse_mode)                                Optional. Send Markdown or HTML, if you want Telegram apps to show bold, italic, fixed-width text or inline URLs in your bot's message.
 * @method $this setEntities(array $entities)                                    Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
 * @method $this setLinkPreviewOptions(LinkPreviewOptions $link_preview_options) Optional. Link preview generation options for the message
 */
class InputTextMessageContent extends InlineEntity implements InputMessageContent
{

}
