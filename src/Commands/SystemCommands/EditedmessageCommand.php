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
 *
 * @todo Remove due to deprecation!
 */
class EditedmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'editedmessage';

    /**
     * @var string
     */
    protected $description = 'User edited message';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Command execute method
     *
     * @return mixed
     */
    public function execute()
    {
        //$update = $this->getUpdate();
        //$edited_message = $update->getEditedMessage();

        trigger_error(__CLASS__ . ' is deprecated and will be removed and handled by ' . GenericmessageCommand::class . ' by default in a future release.', E_USER_DEPRECATED);

        return parent::execute();
    }
}
