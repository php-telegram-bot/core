<?php

namespace PhpTelegramBot\Core\Methods;

use PhpTelegramBot\Core\Entities\InlineQueryResult\InlineQueryResult;
use PhpTelegramBot\Core\Entities\InlineQueryResultsButton;
use PhpTelegramBot\Core\Entities\SentWebAppMessage;

trait AnswersInlineQueries
{
    /**
     * @param array{
     *     inline_query_id: string,
     *     results: InlineQueryResult[],
     *     cache_time: int,
     *     is_personal: bool,
     *     next_offset: string,
     *     button: InlineQueryResultsButton,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function answerInlineQuery(array $data = []): bool
    {
        return $this->send(__FUNCTION__, $data, null);
    }

    /**
     * @param array{
     *     web_app_query_id: string,
     *     result: InlineQueryResult,
     * } $data
     *
     * @throws \PhpTelegramBot\Core\Exceptions\TelegramException
     */
    public function answerWebAppQuery(array $data = []): SentWebAppMessage
    {
        return $this->send(__FUNCTION__, $data, SentWebAppMessage::class);
    }
}
