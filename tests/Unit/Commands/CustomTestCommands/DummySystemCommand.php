<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dummy\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * Test "/dummysystem" command
 */
class DummySystemCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'dummysystem';

    /**
     * @var string
     */
    protected $description = 'Dummy SystemCommand';

    /**
     * @var string
     */
    protected $usage = '/dummysystem';

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
