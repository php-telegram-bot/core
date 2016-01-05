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
use Longman\TelegramBot\Entities\InlineQueryResultArticle;
use Longman\TelegramBot\Entities\Entity;

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
        $query = $inline_query->getQuery();

        $data = [];
        $data['inline_query_id']= $inline_query->getId();

        $articles = [];
        $articles[] = ['id' => '001' , 'title' => 'https://core.telegram.org/bots/api#answerinlinequery', 'message_text' => 'you enter: '.$query ];
        $articles[] = ['id' => '002' , 'title' => 'https://core.telegram.org/bots/api#answerinlinequery', 'message_text' => 'you enter: '.$query ];
        $articles[] = ['id' => '003' , 'title' => 'https://core.telegram.org/bots/api#answerinlinequery', 'message_text' => 'you enter: '.$query ];

        $array_article = [];
        foreach ($articles as $article) {
            $array_article[] = new InlineQueryResultArticle($article);
        }
        $array_json = '['.implode(',', $array_article).']';
        $data['results'] = $array_json;

        $result = Request::answerInlineQuery($data);

        return $result->isOk();
    }
}
