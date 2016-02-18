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

use Longman\TelegramBot\Telegram;

/**
 * Abstract Admin Command Class
 */
abstract class AdminCommand extends Command
{
    /**
     * Constructor
     *
     * @param Telegram $telegram
     */
    public function __construct(Telegram $telegram)
    {
        parent::__construct($telegram);
    }
}
