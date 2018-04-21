<?php
/**
 * This file is part of the TelegramBot package.
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Http;

use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Entities\ChatMember;
use Longman\TelegramBot\Entities\File;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\User;
use Longman\TelegramBot\Entities\UserProfilePhotos;
use Longman\TelegramBot\Entities\WebhookInfo;

/**
 * Class ServerResponse
 *
 * @link https://core.telegram.org/bots/api#making-requests
 *
 * @todo method ResponseParameters getParameters()  Field which can help to automatically handle the error
 */
class Response
{
    /**
     * ServerResponse constructor.
     *
     * @param array $data
     * @param string $bot_username
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data, $bot_username = '')
    {
        // Make sure we don't double-save the raw_data
        unset($data['raw_data']);
        $data['raw_data'] = $data;

        $is_ok = isset($data['ok']) ? (bool) $data['ok'] : false;
        $result = isset($data['result']) ? $data['result'] : null;

        if ($is_ok && is_array($result)) {
            if ($this->isAssoc($result)) {
                $data['result'] = $this->createResultObject($result, $bot_username);
            } else {
                $data['result'] = $this->createResultObjects($result, $bot_username);
            }
        }

        //Make sure we're not raw_data inception-ing
        if (array_key_exists('raw_data', $data)) {
            if ($data['raw_data'] === null) {
                unset($data['raw_data']);
            }
        } else {
            $data['raw_data'] = $data;
        }

        $data['bot_username'] = $bot_username;

        $this->assignMemberVariables($data);
    }

    /**
     * Helper to set member variables
     *
     * @param array $data
     */
    protected function assignMemberVariables(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
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
        return $this->getOk();
    }

    /**
     * If response is ok
     *
     * @return bool
     */
    public function getOk()
    {
        return isset($this->ok) ? (bool) $this->ok : false;
    }

    /**
     * Return result
     *
     * @return mixed
     */
    public function getResult()
    {
        return isset($this->result) ? $this->result : null;
    }

    /**
     * Return error code
     *
     * @return int
     */
    public function getErrorCode()
    {
        return isset($this->error_code) ? $this->error_code : null;
    }

    /**
     * Return human-readable description of the result / unsuccessful request
     *
     * @return string
     */
    public function getDescription()
    {
        return isset($this->description) ? $this->description : null;
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
     * @param array $result
     * @param string $bot_username
     *
     * @return \Longman\TelegramBot\Entities\Chat|\Longman\TelegramBot\Entities\ChatMember|\Longman\TelegramBot\Entities\File|\Longman\TelegramBot\Entities\Message|\Longman\TelegramBot\Entities\User|\Longman\TelegramBot\Entities\UserProfilePhotos|\Longman\TelegramBot\Entities\WebhookInfo
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function createResultObject($result, $bot_username)
    {
        // We don't need to save the raw_data of the response object!
        $result['raw_data'] = null;

        $result_object_types = [
            'total_count' => UserProfilePhotos::class, //Response from getUserProfilePhotos
            'file_id'     => File::class,              //Response from getFile
            'title'       => Chat::class,              //Response from getChat
            'username'    => User::class,              //Response from getMe
            'user'        => ChatMember::class,        //Response from getChatMember
            'url'         => WebhookInfo::class,       //Response from getWebhookInfo
        ];
        foreach ($result_object_types as $type => $object_class) {
            if (isset($result[$type])) {
                return new $object_class($result);
            }
        }

        //Response from sendMessage
        return new Message($result, $bot_username);
    }

    /**
     * Create and return the objects array of the received result
     *
     * @param array $result
     * @param string $bot_username
     *
     * @return null|\Longman\TelegramBot\Entities\ChatMember[]|\Longman\TelegramBot\Entities\Update[]
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function createResultObjects($result, $bot_username)
    {
        $results = [];
        if (isset($result[0]['user'])) {
            //Response from getChatAdministrators
            foreach ($result as $user) {
                // We don't need to save the raw_data of the response object!
                $user['raw_data'] = null;

                $results[] = new ChatMember($user);
            }
        } else {
            //Get Update
            foreach ($result as $update) {
                // We don't need to save the raw_data of the response object!
                $update['raw_data'] = null;

                $results[] = new Update($update, $bot_username);
            }
        }

        return $results;
    }
}
