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

/**
 * New chat title command
 */
class NewchattitleCommand extends Command
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = 'Newchattitle';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'New chat Title';

    /**
     * Version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        //$message = $this->getMessage();
        //$new_chat_title = $message->getNewChatTitle();

        //System command, do nothing
        return true;
    }
}
