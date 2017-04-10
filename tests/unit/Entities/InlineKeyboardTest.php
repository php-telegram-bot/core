<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Tests\Unit;

use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class InlineKeyboardTest extends TestCase
{
    private function getRandomButton($text)
    {
        $random_params = ['url', 'callback_data', 'switch_inline_query', 'switch_inline_query_current_chat'];
        $param         = $random_params[array_rand($random_params, 1)];
        $data          = [
            'text' => $text,
            $param => 'random_param',
        ];

        return new InlineKeyboardButton($data);
    }

    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     * @expectedExceptionMessage inline_keyboard field is not an array!
     */
    public function testInlineKeyboardDataMalformedField()
    {
        new InlineKeyboard(['inline_keyboard' => 'wrong']);
    }

    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     * @expectedExceptionMessage inline_keyboard subfield is not an array!
     */
    public function testInlineKeyboardDataMalformedSubfield()
    {
        new InlineKeyboard(['inline_keyboard' => ['wrong']]);
    }

    public function testInlineKeyboardSingleButtonSingleRow()
    {
        $inline_keyboard = (new InlineKeyboard(
            $this->getRandomButton('Button Text 1')
        ))->getProperty('inline_keyboard');
        self::assertSame('Button Text 1', $inline_keyboard[0][0]->getText());

        $inline_keyboard = (new InlineKeyboard(
            [$this->getRandomButton('Button Text 2')]
        ))->getProperty('inline_keyboard');
        self::assertSame('Button Text 2', $inline_keyboard[0][0]->getText());
    }

    public function testInlineKeyboardSingleButtonMultipleRows()
    {
        $keyboard = (new InlineKeyboard(
            $this->getRandomButton('Button Text 1'),
            $this->getRandomButton('Button Text 2'),
            $this->getRandomButton('Button Text 3')
        ))->getProperty('inline_keyboard');
        self::assertSame('Button Text 1', $keyboard[0][0]->getText());
        self::assertSame('Button Text 2', $keyboard[1][0]->getText());
        self::assertSame('Button Text 3', $keyboard[2][0]->getText());

        $keyboard = (new InlineKeyboard(
            [$this->getRandomButton('Button Text 4')],
            [$this->getRandomButton('Button Text 5')],
            [$this->getRandomButton('Button Text 6')]
        ))->getProperty('inline_keyboard');
        self::assertSame('Button Text 4', $keyboard[0][0]->getText());
        self::assertSame('Button Text 5', $keyboard[1][0]->getText());
        self::assertSame('Button Text 6', $keyboard[2][0]->getText());
    }

    public function testInlineKeyboardMultipleButtonsSingleRow()
    {
        $keyboard = (new InlineKeyboard([
            $this->getRandomButton('Button Text 1'),
            $this->getRandomButton('Button Text 2'),
        ]))->getProperty('inline_keyboard');
        self::assertSame('Button Text 1', $keyboard[0][0]->getText());
        self::assertSame('Button Text 2', $keyboard[0][1]->getText());
    }

    public function testInlineKeyboardMultipleButtonsMultipleRows()
    {
        $keyboard = (new InlineKeyboard(
            [
                $this->getRandomButton('Button Text 1'),
                $this->getRandomButton('Button Text 2'),
            ],
            [
                $this->getRandomButton('Button Text 3'),
                $this->getRandomButton('Button Text 4'),
            ]
        ))->getProperty('inline_keyboard');

        self::assertSame('Button Text 1', $keyboard[0][0]->getText());
        self::assertSame('Button Text 2', $keyboard[0][1]->getText());
        self::assertSame('Button Text 3', $keyboard[1][0]->getText());
        self::assertSame('Button Text 4', $keyboard[1][1]->getText());
    }

    public function testInlineKeyboardAddRows()
    {
        $keyboard_obj = new InlineKeyboard([]);

        $keyboard_obj->addRow($this->getRandomButton('Button Text 1'));
        $keyboard = $keyboard_obj->getProperty('inline_keyboard');
        self::assertSame('Button Text 1', $keyboard[0][0]->getText());

        $keyboard_obj->addRow(
            $this->getRandomButton('Button Text 2'),
            $this->getRandomButton('Button Text 3')
        );
        $keyboard = $keyboard_obj->getProperty('inline_keyboard');
        self::assertSame('Button Text 2', $keyboard[1][0]->getText());
        self::assertSame('Button Text 3', $keyboard[1][1]->getText());

        $keyboard_obj->addRow($this->getRandomButton('Button Text 4'));
        $keyboard = $keyboard_obj->getProperty('inline_keyboard');
        self::assertSame('Button Text 4', $keyboard[2][0]->getText());
    }
}
