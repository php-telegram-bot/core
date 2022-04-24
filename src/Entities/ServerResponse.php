<?php

/**
 * This file is part of the TelegramBot package.
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Entities\ChatMember\ChatMember;
use Longman\TelegramBot\Entities\ChatMember\Factory as ChatMemberFactory;
use Longman\TelegramBot\Entities\Games\GameHighScore;
use Longman\TelegramBot\Entities\MenuButton\Factory as MenuButtonFactory;
use Longman\TelegramBot\Request;

/**
 * Class ServerResponse
 *
 * @link https://core.telegram.org/bots/api#making-requests
 *
 * @method bool   getOk()          If the request was successful
 * @method mixed  getResult()      The result of the query
 * @method int    getErrorCode()   Error code of the unsuccessful request
 * @method string getDescription() Human-readable description of the result / unsuccessful request
 *
 * @todo method ResponseParameters getParameters()  Field which can help to automatically handle the error
 */
class ServerResponse extends Entity
{
    /**
     * ServerResponse constructor.
     *
     * @param array  $data
     * @param string $bot_username
     */
    public function __construct(array $data, string $bot_username = '')
    {
        // Make sure we don't double-save the raw_data
        unset($data['raw_data']);
        $data['raw_data'] = $data;

        $is_ok  = (bool) ($data['ok'] ?? false);
        $result = $data['result'] ?? null;

        if ($is_ok && is_array($result)) {
            if ($this->isAssoc($result)) {
                $data['result'] = $this->createResultObject($result, $bot_username);
            } else {
                $data['result'] = $this->createResultObjects($result, $bot_username);
            }
        }

        parent::__construct($data, $bot_username);
    }

    /**
     * Check if array is associative
     *
     * @link https://stackoverflow.com/a/4254008
     *
     * @param array $array
     *
     * @return bool
     */
    protected function isAssoc(array $array): bool
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    /**
     * If response is ok
     *
     * @return bool
     */
    public function isOk(): bool
    {
        return (bool) $this->getOk();
    }

    /**
     * Print error
     *
     * @see https://secure.php.net/manual/en/function.print-r.php
     *
     * @param bool $return
     *
     * @return bool|string
     */
    public function printError($return = false)
    {
        $error = sprintf('Error N: %s, Description: %s', $this->getErrorCode(), $this->getDescription());

        if ($return) {
            return $error;
        }

        echo $error;

        return true;
    }

    /**
     * Create and return the object of the received result
     *
     * @param array  $result
     * @param string $bot_username
     *
     * @return Chat|ChatMember|File|Message|User|UserProfilePhotos|WebhookInfo
     */
    private function createResultObject(array $result, string $bot_username): Entity
    {
        $result_object_types = [
            'answerWebAppQuery'               => SentWebAppMessage::class,
            'getChat'                         => Chat::class,
            'getMyDefaultAdministratorRights' => ChatAdministratorRights::class,
            'getChatMember'                   => ChatMemberFactory::class,
            'getChatMenuButton'               => MenuButtonFactory::class,
            'getFile'                         => File::class,
            'getMe'                           => User::class,
            'getStickerSet'                   => StickerSet::class,
            'getUserProfilePhotos'            => UserProfilePhotos::class,
            'getWebhookInfo'                  => WebhookInfo::class,
        ];

        $action       = Request::getCurrentAction();
        $object_class = $result_object_types[$action] ?? Message::class;

        // We don't need to save the raw_data of the response object!
        $result['raw_data'] = null;

        return Factory::resolveEntityClass($object_class, $result, $bot_username);
    }

    /**
     * Create and return the objects array of the received result
     *
     * @param array  $results
     * @param string $bot_username
     *
     * @return BotCommand[]|ChatMember[]|GameHighScore[]|Message[]|Update[]
     */
    private function createResultObjects(array $results, string $bot_username): array
    {
        $result_object_types = [
            'getMyCommands'         => BotCommand::class,
            'getChatAdministrators' => ChatMemberFactory::class,
            'getGameHighScores'     => GameHighScore::class,
            'sendMediaGroup'        => Message::class,
        ];

        $action       = Request::getCurrentAction();
        $object_class = $result_object_types[$action] ?? Update::class;

        $objects = [];

        foreach ($results as $result) {
            // We don't need to save the raw_data of the response object!
            $result['raw_data'] = null;

            $objects[] = Factory::resolveEntityClass($object_class, $result, $bot_username);
        }

        return $objects;
    }
}
