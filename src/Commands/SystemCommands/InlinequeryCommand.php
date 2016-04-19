<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineQueryResultArticle;
use Longman\TelegramBot\Request;

/**
 * Inline query command
 */
class InlinequeryCommand extends SystemCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'inlinequery';
    protected $description = 'Reply to inline query';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $update = $this->getUpdate();
        $inline_query = $update->getInlineQuery();
        $query = $inline_query->getQuery();

        $data = ['inline_query_id' => $inline_query->getId()];

        $articles = [
            ['id' => '001', 'title' => 'https://core.telegram.org/bots/api#answerinlinequery', 'message_text' => 'you enter: ' . $query , 'input_message_content' => [ 'message_text' => $query ] ],
            ['id' => '002', 'title' => 'https://core.telegram.org/bots/api#answerinlinequery', 'message_text' => 'you enter: ' . $query , 'input_message_content' => [ 'message_text' => $query ] ],
            ['id' => '003', 'title' => 'https://core.telegram.org/bots/api#answerinlinequery', 'message_text' => 'you enter: ' . $query , 'input_message_content' => [ 'message_text' => $query ] ],
        ];

        $array_article = [];
        foreach ($articles as $article) {
            $array_article[] = new InlineQueryResultArticle($article);
        }
        $data['results'] = '[' . implode(',', $array_article) . ']';

        return Request::answerInlineQuery($data);
    }
}
