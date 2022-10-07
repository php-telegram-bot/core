<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dummy\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * Test "/dummy_user" command
 */
class DummyUserCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'dummy_user';

    /**
     * @var string
     */
    protected $description = 'Dummy UserCommand';

    /**
     * @var string
     */
    protected $usage = '/dummy_user';

    /**
     * Command execute method
     *
     * @return mixed
     */
    public function execute(): ServerResponse
    {
        return Request::emptyResponse();
    }
}
