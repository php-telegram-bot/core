<?php

/*
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

        if (isset($data['ok'])) {
            if ($data['ok']) {
                $this->ok = $data['ok'];
                $this->result = new Message($data['result'], $bot_name);
                $this->error_code = null;
                $this->description = null;
            } else {
                $this->ok = false;
                $this->result = null;
                $this->error_code = $data['error_code'];
                $this->description = $data['description'];
            }
        } else {
            throw new TelegramException('ok is not set!');
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
//Succes request
//Array
//(
//    [ok] => 1
//    [result] => Array
//        (
//            [message_id] => 3582
//            [from] => Array
//                (
//                    [id] => 12345678
//                    [first_name] => name
//                    [username] => botname
//                )
//
//            [chat] => Array
//                (
//                    [id] => 123456789
//                    [first_name] => name
//                    [username] => Surname
//                )
//
//            [date] => 1441194780
//            [text] => hello 
//        )
//
//)

// Error Request
//
//Array
//(
//    [ok] => 
//    [error_code] => 401
//    [description] => Error: Unauthorized
//)

//Array
//(
//    [chat_id] => 110751663
//    [text] => ciao
//)
}
