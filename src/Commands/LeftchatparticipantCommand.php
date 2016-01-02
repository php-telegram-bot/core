<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

class LeftchatparticipantCommand extends Command
{
    protected $name = 'leftchatparticipant';
    protected $description = 'Left Chat Participant';
    protected $usage = '/';
    protected $version = '1.0.0';
    protected $enabled = true;

    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();

        $participant = $message->getLeftChatParticipant();

        // temporary do nothing
        return 1;
    }
}
