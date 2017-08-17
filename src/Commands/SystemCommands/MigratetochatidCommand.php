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
 * Migrate to chat id command
 */
class MigratetochatidCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'migratetochatid';

    /**
     * @var string
     */
    protected $description = 'Migrate to chat id';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        //$message = $this->getMessage();
        //$migrate_to_chat_id = $message->getMigrateToChatId();

        return parent::execute();
    }
}
