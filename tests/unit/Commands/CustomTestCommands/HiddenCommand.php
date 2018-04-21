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
use Longman\TelegramBot\Http\Client;
use Longman\TelegramBot\Http\Response;

/**
 * Test "/hidden" command to test $show_in_help
 */
class HiddenCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'hidden';

    /**
     * @var string
     */
    protected $description = 'This command is hidden in help';

    /**
     * @var string
     */
    protected $usage = '/hidden';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var bool
     */
    protected $show_in_help = false;

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        return new Response(['ok' => true, 'result' => true]);
    }
}
