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
        $keyboard = new InlineKeyboard(
            $this->getRandomButton('Button Text 1')
        );
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['Button Text 1'],
        ], 'text', $keyboard);

        $keyboard = new InlineKeyboard(
            [$this->getRandomButton('Button Text 2')]
        );
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['Button Text 2'],
        ], 'text', $keyboard);
    }

    public function testInlineKeyboardSingleButtonMultipleRows()
    {
        $keyboard = new InlineKeyboard(
            $this->getRandomButton('Button Text 1'),
            $this->getRandomButton('Button Text 2'),
            $this->getRandomButton('Button Text 3')
        );
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['Button Text 1'],
            ['Button Text 2'],
            ['Button Text 3'],
        ], 'text', $keyboard);

        $keyboard = new InlineKeyboard(
            [$this->getRandomButton('Button Text 4')],
            [$this->getRandomButton('Button Text 5')],
            [$this->getRandomButton('Button Text 6')]
        );
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['Button Text 4'],
            ['Button Text 5'],
            ['Button Text 6'],
        ], 'text', $keyboard);
    }

    public function testInlineKeyboardMultipleButtonsSingleRow()
    {
        $keyboard = new InlineKeyboard([
            $this->getRandomButton('Button Text 1'),
            $this->getRandomButton('Button Text 2'),
        ]);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['Button Text 1', 'Button Text 2'],
        ], 'text', $keyboard);
    }

    public function testInlineKeyboardMultipleButtonsMultipleRows()
    {
        $keyboard = new InlineKeyboard(
            [
                $this->getRandomButton('Button Text 1'),
                $this->getRandomButton('Button Text 2'),
            ],
            [
                $this->getRandomButton('Button Text 3'),
                $this->getRandomButton('Button Text 4'),
            ]
        );

        KeyboardTest::assertAllButtonPropertiesEqual([
            ['Button Text 1', 'Button Text 2'],
            ['Button Text 3', 'Button Text 4'],
        ], 'text', $keyboard);
    }

    public function testInlineKeyboardAddRows()
    {
        $keyboard = new InlineKeyboard([]);

        $keyboard->addRow($this->getRandomButton('Button Text 1'));
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['Button Text 1'],
        ], 'text', $keyboard);

        $keyboard->addRow(
            $this->getRandomButton('Button Text 2'),
            $this->getRandomButton('Button Text 3')
        );
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['Button Text 1'],
            ['Button Text 2', 'Button Text 3'],
        ], 'text', $keyboard);

        $keyboard->addRow($this->getRandomButton('Button Text 4'));
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['Button Text 1'],
            ['Button Text 2', 'Button Text 3'],
            ['Button Text 4'],
        ], 'text', $keyboard);
    }

    public function testInlineKeyboardPagination()
    {
        // Should get '_page_%d' appended to it.
        $callback_data = 'cbdata';

        // current
        $keyboard = InlineKeyboard::getPagination($callback_data, 1, 1);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['· 1 ·'],
        ], 'text', $keyboard);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['cbdata_page_1'],
        ], 'callback_data', $keyboard);

        // current, next, last
        $keyboard = InlineKeyboard::getPagination($callback_data, 1, 10);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['· 1 ·', '2 ›', '10 »'],
        ], 'text', $keyboard);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['cbdata_page_1', 'cbdata_page_2', 'cbdata_page_10'],
        ], 'callback_data', $keyboard);

        // first, previous, current, next, last
        $keyboard = InlineKeyboard::getPagination($callback_data, 5, 10);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['« 1', '‹ 4', '· 5 ·', '6 ›', '10 »'],
        ], 'text', $keyboard);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['cbdata_page_1', 'cbdata_page_4', 'cbdata_page_5', 'cbdata_page_6', 'cbdata_page_10'],
        ], 'callback_data', $keyboard);

        // first, previous, current, last
        $keyboard = InlineKeyboard::getPagination($callback_data, 9, 10);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['« 1', '‹ 8', '· 9 ·', '10 »'],
        ], 'text', $keyboard);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['cbdata_page_1', 'cbdata_page_8', 'cbdata_page_9', 'cbdata_page_10'],
        ], 'callback_data', $keyboard);

        // first, previous, current
        $keyboard = InlineKeyboard::getPagination($callback_data, 10, 10);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['« 1', '‹ 9', '· 10 ·'],
        ], 'text', $keyboard);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['cbdata_page_1', 'cbdata_page_9', 'cbdata_page_10'],
        ], 'callback_data', $keyboard);

        // custom labels, skipping some buttons
        // first, previous, current, next, last
        $keyboard = InlineKeyboard::getPagination($callback_data, 5, 10, [
            'first'    => '',
            'previous' => 'previous %d',
            'current'  => null,
            'next'     => '%d next',
            'last'     => '%d last',
        ]);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['previous 4', '6 next', '10 last'],
        ], 'text', $keyboard);
        KeyboardTest::assertAllButtonPropertiesEqual([
            ['cbdata_page_4', 'cbdata_page_6', 'cbdata_page_10'],
        ], 'callback_data', $keyboard);
    }
}
