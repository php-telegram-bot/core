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
        //$inline_query->getQuery();
        //$update->getUpdateId();
        $data = array();
        $data['inline_query_id']= $update->getUpdateId();
        $data['inline_query_id']= (string) time();
        //$data['cache_time']=60;
        //$data['is_personal']="false";
        //$data['next_offset']="122;
        $data['results']='[
          {
            "type": "article",
            "id": "001",
            "title": "UC Browser",
            "message_text": "Text of the first message",
            "parse_mode": "Markdown",
            "disable_web_page_preview": true,
            "url": "telegram.com",
            "hide_url": true,
            "description": "Optional. Short description of the result",
            "thumb_url": "http://icons.iconarchive.com/icons/martz90/circle/64/uc-browser-icon.png",
            "thumb_width": 64,
            "thumb_height": 64
          },
          {
            "type": "article",
            "id": "002",
            "title": "Bitcoin",
            "message_text": "*Text of the second message*",
            "parse_mode": "Markdown",
            "disable_web_page_preview": true,
            "url": "bitcoin.org",
            "hide_url": true,
            "description": "Short description of the result",
            "thumb_url": "http://www.coinwarz.com/content/images/bitcoin-64x64.png",
            "thumb_width": 64,
            "thumb_height": 64
          }
        ]';

        $result = Request::answerInlineQuery($data);


        return $result->isOk();
        //return 1;
    }
}
