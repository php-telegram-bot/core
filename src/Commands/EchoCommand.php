<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

/**
 * User "/echo" command
 */
class EchoCommand extends Command
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = 'echo';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Show text';

    /**
     * Usage
     *
     * @var string
     */
    protected $usage = '/echo <text>';

    /**
     * Version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * If this command is enabled
     *
     * @var boolean
     */
    protected $enabled = true;

    /**
     * If this command is public
     *
     * @var boolean
     */
    protected $public = true;

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $text = $message->getText(true);

        $data = [];
        $data['chat_id'] = $chat_id;
        $data['text'] = $text;

        $result = Request::sendMessage($data);
        return $result->isOk();
    }
}
