<?php
/**
 * This file is part of the TelegramBot package.
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

class ServerResponse extends Entity
{
    /**
     * @var bool
     */
    protected $ok;

    /**
     * @var null
     */
    protected $result;

    /**
     * @var null
     */
    protected $error_code;

    /**
     * @var null
     */
    protected $description;

    /**
     * ServerResponse constructor.
     *
     * @param array $data
     * @param       $bot_name
     */
    public function __construct(array $data, $bot_name)
    {
        if (isset($data['ok']) && isset($data['result'])) {
            if (is_array($data['result'])) {
                if ($data['ok'] && !$this->isAssoc($data['result']) && !isset($data['result'][0]['user'])) {
                    //Get Update
                    foreach ($data['result'] as $update) {
                        $this->result[] = new Update($update, $bot_name);
                    }
                } elseif ($data['ok'] && !$this->isAssoc($data['result']) && isset($data['result'][0]['user'])) {
                    //Response from getChatAdministrators
                    $this->result = [];
                    foreach ($data['result'] as $user) {
                        array_push($this->result, new ChatMember($user));
                    }
                } elseif ($data['ok'] && $this->isAssoc($data['result'])) {
                    if (isset($data['result']['total_count'])) {
                        //Response from getUserProfilePhotos
                        $this->result = new UserProfilePhotos($data['result']);
                    } elseif (isset($data['result']['file_id'])) {
                        //Response from getFile
                        $this->result = new File($data['result']);
                    } elseif (isset($data['result']['username'])) {
                        //Response from getMe
                        $this->result = new User($data['result']);
                    } elseif (isset($data['result']['id'])) {
                        //Response from getChat
                        $this->result = new Chat($data['result']);
                    } elseif (isset($data['result']['user'])) {
                        //Response from getChatMember
                        $this->result = new ChatMember($data['result']);
                    } else {
                        //Response from sendMessage
                        $this->result = new Message($data['result'], $bot_name);
                    }
                }

                $this->ok          = $data['ok'];
                $this->error_code  = null;
                $this->description = null;
            } else {
                if ($data['ok'] && $data['result'] === true) {
                    //Response from setWebhook set
                    $this->ok         = $data['ok'];
                    $this->result     = true;
                    $this->error_code = null;

                    if (isset($data['description'])) {
                        $this->description = $data['description'];
                    } else {
                        $this->description = '';
                    }
                } elseif (is_numeric($data['result'])) {
                    //Response from getChatMembersCount
                    $this->result = $data['result'];
                } else {
                    $this->ok          = false;
                    $this->result      = null;
                    $this->error_code  = $data['error_code'];
                    $this->description = $data['description'];
                }
            }
        } else {
            //webHook not set
            $this->ok = false;

            if (isset($data['result'])) {
                $this->result = $data['result'];
            } else {
                $this->result = null;
            }

            if (isset($data['error_code'])) {
                $this->error_code = $data['error_code'];
            } else {
                $this->error_code = null;
            }

            if (isset($data['description'])) {
                $this->description = $data['description'];
            } else {
                $this->description = null;
            }

            //throw new TelegramException('ok(variable) is not set!');
        }
    }

    /**
     * Check if array is associative
     *
     * @param array $array
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
        return $this->ok;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Get error code
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->error_code;
    }

    /**
     * Get description
     *
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
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
}
