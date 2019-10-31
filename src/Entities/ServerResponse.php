<?php

/**
 * This file is part of the TelegramBot package.
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Entities\Games\GameHighScore;
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
    public function __construct(array $data, $bot_username)
    {
        // Make sure we don't double-save the raw_data
        unset($data['raw_data']);
        $data['raw_data'] = $data;

        $is_ok  = isset($data['ok']) ? (bool) $data['ok'] : false;
        $result = isset($data['result']) ? $data['result'] : null;

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
    protected function isAssoc(array $array)
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    /**
     * If response is ok
     *
     * @return bool
     */
    public function isOk()
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
    private function createResultObject(array $result, $bot_username)
    {
        $action = Request::getCurrentAction();

        // We don't need to save the raw_data of the response object!
        $result['raw_data'] = null;

        $result_object_types = [
            'getChat'              => Chat::class,
            'getChatMember'        => ChatMember::class,
            'getFile'              => File::class,
            'getMe'                => User::class,
            'getStickerSet'        => StickerSet::class,
            'getUserProfilePhotos' => UserProfilePhotos::class,
            'getWebhookInfo'       => WebhookInfo::class,
        ];

        $object_class = array_key_exists($action, $result_object_types) ? $result_object_types[$action] : Message::class;

        return new $object_class($result, $bot_username);
    }

    /**
     * Create and return the objects array of the received result
     *
     * @param array  $result
     * @param string $bot_username
     *
     * @return ChatMember[]|GameHighScore[]|Message[]|Update[]
     */
    private function createResultObjects(array $result, $bot_username)
    {
        $results = [];
        $action  = Request::getCurrentAction();

        $result_object_types = [
            'getChatAdministrators' => ChatMember::class,
            'getGameHighScores'     => GameHighScore::class,
            'sendMediaGroup'        => Message::class,
        ];

        $object_class = array_key_exists($action, $result_object_types) ? $result_object_types[$action] : Update::class;

        foreach ($result as $data) {
            // We don't need to save the raw_data of the response object!
            $data['raw_data'] = null;

            $results[] = new $object_class($data, $bot_username);
        }

        return $results;
    }
}
