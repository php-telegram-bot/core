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
 * Class Response
 *
 * @link https://core.telegram.org/bots/api#making-requests
 *
 * @todo method ResponseParameters getParameters()  Field which can help to automatically handle the error
 */
class Response
{
    /** @var bool */
    protected $ok = false;

    /** @var string */
    protected $description;

    /** @var array */
    protected $result;

    /** @var int */
    protected $error_code;

    /** @var array */
    protected $parameters;

    /** @var array */
    protected $raw_data;

    /** @var string */
    protected $bot_username;

    /**
     * Response constructor.
     *
     * @param array $data
     * @param string $bot_username
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data, $bot_username = '')
    {
        $this->raw_data = $data;

        $this->ok = $data['ok'];

        if (isset($data['description'])) {
            $this->description = $data['description'];
        }

        if (isset($data['error_code'])) {
            $this->error_code = $data['error_code'];
        }

        if (isset($data['parameters'])) {
            $this->parameters = $data['parameters'];
        }

        $this->bot_username = $bot_username;

        $result = isset($data['result']) ? $data['result'] : null;
        if ($this->ok && is_array($result)) {
            if ($this->isAssoc($result)) {
                $this->result = $this->createResultObject($result, $bot_username);
            } else {
                $this->result = $this->createResultObjects($result, $bot_username);
            }
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
        return $this->ok;
    }

    /**
     * Return result
     *
     * @return array
     */
    public function getResult()
    {
        return ! empty($this->result) ? $this->result : [];
    }

    /**
     * Return error code
     *
     * @return int
     */
    public function getErrorCode()
    {
        return ! empty($this->error_code) ? $this->error_code : null;
    }

    /**
     * Return human-readable description of the result / unsuccessful request
     *
     * @return string
     */
    public function getDescription()
    {
        return ! empty($this->description) ? $this->description : null;
    }

    /**
     * Get error
     *
     * @see https://secure.php.net/manual/en/function.print-r.php
     *
     * @return string
     */
    public function getError()
    {
        $error = sprintf('Error N: %s, Description: %s', $this->getErrorCode(), $this->getDescription());

        return $error;
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
    protected function createResultObject($result, $bot_username)
    {
        $result_object_types = [
            'total_count' => UserProfilePhotos::class, // Response from getUserProfilePhotos
            'file_id'     => File::class,              // Response from getFile
            'title'       => Chat::class,              // Response from getChat
            'username'    => User::class,              // Response from getMe
            'user'        => ChatMember::class,        // Response from getChatMember
            'url'         => WebhookInfo::class,       // Response from getWebhookInfo
        ];
        foreach ($result_object_types as $type => $object_class) {
            if (isset($result[$type])) {
                return new $object_class($result, $bot_username);
            }
        }

        // Response from sendMessage
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
    protected function createResultObjects($result, $bot_username)
    {
        $results = [];
        if (isset($result[0]['user'])) {
            // Response from getChatAdministrators
            foreach ($result as $user) {
                // We don't need to save the raw_data of the response object!
                $user['raw_data'] = null;

                $results[] = new ChatMember($user);
            }
        } else {
            // Get Update
            foreach ($result as $update) {
                // We don't need to save the raw_data of the response object!
                $update['raw_data'] = null;

                $results[] = new Update($update, $bot_username);
            }
        }

        return $results;
    }
}
