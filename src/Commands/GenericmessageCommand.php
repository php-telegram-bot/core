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
 * Generic message command
 */
class GenericmessageCommand extends Command
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = 'Genericmessage';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * Usage
     *
     * @var string
     */
    protected $usage = '/';

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
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        //you can use $command as param
        $command = $message->getCommand();
 
        $chat_id = $message->getChat()->getId();
        $text = $message->getText(true);
        //Do nothing
        return 1;
    }
}
