<?php
/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by Marco Boretto <marco.bore@gmail.com>
*/
namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Exception\TelegramException;

class ReplyMarkup extends Entity
{
    protected $reply_markup_array;

    public function __construct()
    {
        $this->reply_markup_array = array();
    }
 
    public function addKeyBoard($options, $resize = false, $once = false, $selective = false)
    {
        if (is_array($options)) {
            $list = array();
            foreach ($options as $item) {
                if (is_array($item)) {
                    $list[] = $item;
                } else {
                    $list[] = array($item);
                }
            }
            $this->reply_markup_array['keyboard'] = $list;

            if ($resize == true) {
                $this->reply_markup_array['resize_keyboard'] = true;
            }
            
            if ($once == true) {
                $this->reply_markup_array['one_time_keyboard'] = true;
            }

            $this->addSelective($selective);

            return true;
        }
        return false;
    }
   
    public function addKeyBoardHide($selective = false)
    {
        $this->reply_markup_array['hide_keyboard'] = true;
        $this->addSelective($selective);
    }
    
    public function addForceReply($selective = false)
    {
        $this->reply_markup_array['force_reply'] = true;
        $this->addSelective($selective);
    }

    protected function addSelective($selective = false)
    {
        if ($selective == true) {
            $this->reply_markup_array['selective'] = true;
        }
    }

    public function getJsonQuery()
    {
        return json_encode($this->reply_markup_array);
    }
}
