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
 * Edited message command
 */
class EditedmessageCommand extends SystemCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'editedmessage';
    protected $description = 'User edited message';
    protected $version = '1.0.0';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    /*public function execute()
    {
        $update = $this->getUpdate();
        $edited_message = $update->getEditedMessage();
    }*/
}
