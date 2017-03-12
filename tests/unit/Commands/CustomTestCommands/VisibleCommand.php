<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * Test "/visible" command to test $show_in_help
 */
class VisibleCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'visible';

    /**
     * @var string
     */
    protected $description = 'This command is visible in help';

    /**
     * @var string
     */
    protected $usage = '/visible';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var bool
     */
    protected $show_in_help = true;

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        return Request::emptyResponse();
    }
}
