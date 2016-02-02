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

class LeftchatparticipantCommand extends Command
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = 'leftchatparticipant';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Left Chat Participant';

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

        $participant = $message->getLeftChatParticipant();

        // temporary do nothing
        return 1;
    }
}
