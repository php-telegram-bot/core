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
use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\InlineQueryResultArticle;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

/**
 * Chosen inline result command
 */
class ChoseninlineresultCommand extends Command
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = 'choseninlineresult';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Chosen result query';

    /**
     * Usage
     *
     * @var string
     */
    protected $usage = '';

    /**
     * Version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * If this command is enabled
     *
     * @var boolean
     */
    protected $enabled = true;

    /**
     * If this command is public
     *
     * @var boolean
     */
    protected $public = false;

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        $update = $this->getUpdate();
        $inline_query = $update->getChosenInlineResult();
        $query = $inline_query->getQuery();

        //Information about chosen result is returned
        //Do nothing

        return 1;
    }
}
