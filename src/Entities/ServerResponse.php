<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * ServerResponse.php
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
            if ($data['ok'] & is_array($data['result'])) {
                //Response from sendMessage set
                $this->ok = $data['ok'];
                $this->result = new Message($data['result'], $bot_name);
                $this->error_code = null;
                $this->description = null;

            } elseif ($data['ok'] & $data['result'] == true) {
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
}
