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
 * Left chat participant command
 */
class LeftchatparticipantCommand extends Command
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'leftchatparticipant';
    protected $description = 'Left Chat Participant';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        //$message = $this->getMessage();
        //$participant = $message->getLeftChatParticipant();

        //System command, do nothing
        return true;
    }
}
