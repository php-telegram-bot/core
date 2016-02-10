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
 * Left chat participant command
 */
class LeftchatparticipantCommand extends Command
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'leftchatparticipant';
    protected $description = 'Left Chat Participant';
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

        $participant = $message->getLeftChatParticipant();

        //Temporary, do nothing
        return 1;
    }
}
