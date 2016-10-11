<?php
/**
 * This file is part of the TelegramBot package.
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

/**
 * Class ServerResponse
 *
 * @method bool   getOk()          If the request was successful
 * @method mixed  getResult()      The result of the query
 * @method int    getErrorCode()   Error code of the unsuccessful request
 * @method string getDescription() Human-readable description of the result / unsuccessful request
 *
 * @method_todo ResponseParameters getParameters()  Field which can help to automatically handle the error
 */
class ServerResponse extends Entity
{
    /**
     * ServerResponse constructor.
     *
     * @param array  $data
     * @param string $bot_name
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data, $bot_name)
    {
        // Make sure we don't double-save the raw_data.
        unset($data['raw_data']);
        $data['raw_data'] = $data;

        $is_ok  = isset($data['ok']) ? (bool)$data['ok'] : false;
        $result = isset($data['result']) ? $data['result'] : null;

        if ($is_ok && $result !== null) {
            $data['ok'] = true;

            if (is_array($result)) {
                if ($this->isAssoc($result)) {
                    $data['result'] = $this->createResultObject($result, $bot_name);
                } else {
                    $data['result'] = $this->createResultObjects($result, $bot_name);
                }

                unset($data['error_code'], $data['description']);
            } else {
                $data['result'] = $result;
                if ($result === true) {
                    //Response from setWebhook set
                    unset($data['error_code']);
                } elseif (!is_numeric($result)) { //Not response from getChatMembersCount
                    $data['ok'] = false;
                    unset($data['result']);
                }
            }
        } else {
            //webHook not set
            $data['ok'] = false;
        }

        parent::__construct($data, $bot_name);
    }

    /**
     * Check if array is associative
     *
     * @param array $array
     *
     * @return bool
     */
    protected function isAssoc(array $array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

    /**
     * If response is ok
     *
     * @return bool
     */
    public function isOk()
    {
        return (bool)$this->getOk();
    }

    /**
     * Print error
     *
     * @return string
     */
    public function printError()
    {
        return 'Error N: ' . $this->getErrorCode() . ' Description: ' . $this->getDescription();
    }

    /**
     * Create and return the object of the received result
     *
     * @param array  $result
     * @param string $bot_name
     *
     * @return \Longman\TelegramBot\Entities\Chat|\Longman\TelegramBot\Entities\ChatMember|\Longman\TelegramBot\Entities\File|\Longman\TelegramBot\Entities\Message|\Longman\TelegramBot\Entities\User|\Longman\TelegramBot\Entities\UserProfilePhotos|\Longman\TelegramBot\Entities\WebhookInfo
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function createResultObject($result, $bot_name)
    {
        // We don't need to save the raw_data of the response object!
        $result['raw_data'] = null;

        $result_object_types = [
            'total_count' => 'UserProfilePhotos', //Response from getUserProfilePhotos
            'file_id'     => 'File',              //Response from getFile
            'username'    => 'User',              //Response from getMe
            'id'          => 'Chat',              //Response from getChat
            'user'        => 'ChatMember',        //Response from getChatMember
            'url'         => 'WebhookInfo',       //Response from getWebhookInfo
        ];
        foreach ($result_object_types as $type => $object_class) {
            if (isset($result[$type])) {
                $object_class = __NAMESPACE__ . '\\' . $object_class;

                return new $object_class($result);
            }
        }

        //Response from sendMessage
        return new Message($result, $bot_name);
    }

    /**
     * Create and return the objects array of the received result
     *
     * @param array  $result
     * @param string $bot_name
     *
     * @return null|\Longman\TelegramBot\Entities\ChatMember[]|\Longman\TelegramBot\Entities\Update[]
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function createResultObjects($result, $bot_name)
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

                $results[] = new Update($update, $bot_name);
            }
        }

        return $results;
    }
}
