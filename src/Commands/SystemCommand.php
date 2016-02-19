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

/**
 * Abstract System Command Class
 */
abstract class SystemCommand extends Command
{
    /**
     * A system command just executes
     *
     * Although system commands should just work and return 'true',
     * each system command can override this method to add custom functionality.
     *
     * @return bool
     */
    public function execute()
    {
        //System command, do nothing
        return true;
    }
}
