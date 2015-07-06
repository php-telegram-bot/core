<?php
/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\Unit\Commands;

use \Tests\Unit\TestCase;
use \Longman\TelegramBot\Telegram;

class CalcCommandTest extends TestCase
{
	/**
	* @var \Longman\TelegramBot\Telegram
	*/
	private $telegram;


	/**
	* @var int
	*/
	private $message_id = 1;


	/**
	* @var int
	*/
	private $chat_id = 2;

	/**
	* setUp
	*/
	protected function setUp()
	{
		$this->telegram = new Telegram('testapikey', 'testbotname');
		$msg = '\/calc 2+2';
		$update = '{"update_id":'.(int)microtime(true).',"message":{"message_id":'.$this->message_id.',"from":{"id":100000,"first_name":"Avtandil","last_name":"Kikabidze","username":"LONGMAN"},"chat":{"id":'.$this->chat_id.',"first_name":"Avtandil","last_name":"Kikabidze","username":"LONGMAN"},"date":1435507990,"text":"'.$msg.'"}}';
		$this->telegram->setCustomUpdate($update);
	}



    /**
     * @test
     */
    public function execute() {
		$result = $this->telegram->handle();
		$this->assertEquals('4', $result['text']);
		$this->assertEquals($this->message_id, $result['reply_to_message_id']);
		$this->assertEquals($this->chat_id, $result['chat_id']);
    }


}

