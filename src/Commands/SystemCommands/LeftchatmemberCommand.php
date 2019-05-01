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
 * Left chat member command
 *
 * @todo Remove due to deprecation!
 */
class LeftchatmemberCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'leftchatmember';

    /**
     * @var string
     */
    protected $description = 'Left Chat Member';

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
        //$member = $message->getLeftChatMember();

        trigger_error(__CLASS__ . ' is deprecated and will be removed and handled by ' . GenericmessageCommand::class . ' by default in a future release.', E_USER_DEPRECATED);

        return parent::execute();
    }
}
