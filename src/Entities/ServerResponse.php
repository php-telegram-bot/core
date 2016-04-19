<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Exception\TelegramException;

class ServerResponse extends Entity
{

    protected $ok;
    protected $result;
    protected $error_code;
    protected $description;


    public function __construct(array $data, $bot_name)
    {
        if (isset($data['ok']) & isset($data['result'])) {
            if (is_array($data['result'])) {
                if ($data['ok'] & !$this->isAssoc($data['result'])) {
                    //get update
                    foreach ($data['result'] as $update) {
                        $this->result[] = new Update($update, $bot_name);
                    }
                } elseif ($data['ok'] & $this->isAssoc($data['result'])) {
                    if (isset($data['result']['total_count'])) {
                        //getUserProfilePhotos
                        $this->result = new UserProfilePhotos($data['result']);
                    } elseif (isset($data['result']['file_id'])) {
                        //Response getFile
                        $this->result = new File($data['result']);
                    } elseif (isset($data['result']['username'])) {
                        //Response getMe
                        $this->result = new User($data['result']);
                    } else {
                        //Response from sendMessage
                        $this->result = new Message($data['result'], $bot_name);
                    }
                }
    
                $this->ok = $data['ok'];
                $this->error_code = null;
                $this->description = null;
            } else {
                if ($data['ok'] & $data['result'] == true) {
                    //Response from setWebhook set
                    $this->ok = $data['ok'];
                    $this->result = true;
                    $this->error_code = null;
    
                    if (isset($data['description'])) {
                        $this->description = $data['description'];
                    } else {
                        $this->description = '';
                    }
                } else {
                    $this->ok = false;
                    $this->result = null;
                    $this->error_code = $data['error_code'];
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

    //must be an array
    protected function isAssoc(array $array)
    {
        return (bool) count(array_filter(array_keys($array), 'is_string'));
    }
    public function isOk()
    {
        return $this->ok;
    }
    public function getResult()
    {
        return $this->result;
    }
    public function getErrorCode()
    {
        return $this->error_code;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function printError()
    {
        return 'Error N: '.$this->getErrorCode().' Description: '.$this->getDescription();
    }
}
