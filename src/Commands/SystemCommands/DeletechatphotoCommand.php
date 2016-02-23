<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;

/**
 * Delete chat photo command
 */
class DeletechatphotoCommand extends SystemCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Deletechatphoto';
    protected $description = 'Delete chat photo';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    /*public function execute()
    {
        //$message = $this->getMessage();
        //$delete_chat_photo = $message->getDeleteChatPhoto();
    }*/
}
