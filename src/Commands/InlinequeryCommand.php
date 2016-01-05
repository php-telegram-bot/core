<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

class InlinequeryCommand extends Command
{
    protected $name = 'inlinequery';
    protected $description = 'Reply to inline query';
    protected $usage = '';
    protected $version = '1.0.0';
    protected $enabled = true;
    protected $public = false;

    public function execute()
    {
        $update = $this->getUpdate();
        $inline_query = $update->getInlineQuery();
        echo $inline_query->getQuery();
        

        //$result = Request::sendMessage($data);
        //return $result->isOk();
        return 1;
    }
}
