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
 * Migrate from chat id command
 */
class MigratefromchatidCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'migratefromchatid';

    /**
     * @var string
     */
    protected $description = 'Migrate from chat id';

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
        //$migrate_from_chat_id = $message->getMigrateFromChatId();

        return parent::execute();
    }
}
