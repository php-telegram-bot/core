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
 * New chat title command
 */
class NewchattitleCommand extends Command
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Newchattitle';
    protected $description = 'New chat Title';
    protected $usage = '/';
    protected $version = '1.0.0';
    protected $enabled = true;
    /**#@-*/

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();

        $new_chat_title = $message->getNewChatTitle();

        //Temporary, do nothing
        return 1;
    }
}
