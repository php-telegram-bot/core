<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\ReplyKeyboardMarkup;

/**
 * User "/markdown" command
 */
class MarkdownCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'markdown';
    protected $description = 'Print Markdown tesxt';
    protected $usage = '/markdown';
    protected $version = '1.0.0';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $text = $message->getText(true);

        $data = [];
        $data['chat_id'] = $chat_id;
        $data['parse_mode'] = 'MARKDOWN';
        $data['text'] = "*bold* _italic_ `inline fixed width code` ```preformatted code block
code block
```
[Best Telegram bot api!!](https://github.com/akalongman/php-telegram-bot)

";

        return Request::sendMessage($data);
    }
}
