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
 * Generic message command
 */
class GenericmessageCommand extends Command
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = 'Genericmessage';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * Version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        //System command, do nothing
        return true;
    }
}
