<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dummy\AdminCommands;

use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * Test "/dummy_admin" command
 */
class DummyAdminCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'dummy_admin';

    /**
     * @var string
     */
    protected $description = 'Dummy AdminCommand';

    /**
     * @var string
     */
    protected $usage = '/dummy_admin';

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
