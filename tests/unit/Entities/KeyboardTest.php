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

use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\KeyboardButton;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class KeyboardTest extends TestCase
{
    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     * @expectedExceptionMessage keyboard field is not an array!
     */
    public function testKeyboardDataMalformedField()
    {
        new Keyboard(['keyboard' => 'wrong']);
    }

    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     * @expectedExceptionMessage keyboard subfield is not an array!
     */
    public function testKeyboardDataMalformedSubfield()
    {
        new Keyboard(['keyboard' => ['wrong']]);
    }

    public function testKeyboardSingleButtonSingleRow()
    {
        $keyboard = (new Keyboard('Button Text 1'))->getProperty('keyboard');
        self::assertSame('Button Text 1', $keyboard[0][0]->getText());

        $keyboard = (new Keyboard(['Button Text 2']))->getProperty('keyboard');
        self::assertSame('Button Text 2', $keyboard[0][0]->getText());
    }

    public function testKeyboardSingleButtonMultipleRows()
    {
        $keyboard = (new Keyboard(
            'Button Text 1',
            'Button Text 2',
            'Button Text 3'
        ))->getProperty('keyboard');
        self::assertSame('Button Text 1', $keyboard[0][0]->getText());
        self::assertSame('Button Text 2', $keyboard[1][0]->getText());
        self::assertSame('Button Text 3', $keyboard[2][0]->getText());

        $keyboard = (new Keyboard(
            ['Button Text 4'],
            ['Button Text 5'],
            ['Button Text 6']
        ))->getProperty('keyboard');
        self::assertSame('Button Text 4', $keyboard[0][0]->getText());
        self::assertSame('Button Text 5', $keyboard[1][0]->getText());
        self::assertSame('Button Text 6', $keyboard[2][0]->getText());
    }

    public function testKeyboardMultipleButtonsSingleRow()
    {
        $keyboard = (new Keyboard(['Button Text 1', 'Button Text 2']))->getProperty('keyboard');
        self::assertSame('Button Text 1', $keyboard[0][0]->getText());
        self::assertSame('Button Text 2', $keyboard[0][1]->getText());
    }

    public function testKeyboardMultipleButtonsMultipleRows()
    {
        $keyboard = (new Keyboard(
            ['Button Text 1', 'Button Text 2'],
            ['Button Text 3', 'Button Text 4']
        ))->getProperty('keyboard');

        self::assertSame('Button Text 1', $keyboard[0][0]->getText());
        self::assertSame('Button Text 2', $keyboard[0][1]->getText());
        self::assertSame('Button Text 3', $keyboard[1][0]->getText());
        self::assertSame('Button Text 4', $keyboard[1][1]->getText());
    }

    public function testKeyboardWithButtonObjects()
    {
        $keyboard = (new Keyboard(
            new KeyboardButton('Button Text 1')
        ))->getProperty('keyboard');
        self::assertSame('Button Text 1', $keyboard[0][0]->getText());

        $keyboard = (new Keyboard(
            new KeyboardButton('Button Text 2'),
            new KeyboardButton('Button Text 3')
        ))->getProperty('keyboard');
        self::assertSame('Button Text 2', $keyboard[0][0]->getText());
        self::assertSame('Button Text 3', $keyboard[1][0]->getText());

        $keyboard = (new Keyboard(
            [new KeyboardButton('Button Text 4')],
            [new KeyboardButton('Button Text 5'), new KeyboardButton('Button Text 6')]
        ))->getProperty('keyboard');
        self::assertSame('Button Text 4', $keyboard[0][0]->getText());
        self::assertSame('Button Text 5', $keyboard[1][0]->getText());
        self::assertSame('Button Text 6', $keyboard[1][1]->getText());
    }

    public function testKeyboardWithDataArray()
    {
        $resize_keyboard   = (bool) mt_rand(0, 1);
        $one_time_keyboard = (bool) mt_rand(0, 1);
        $selective         = (bool) mt_rand(0, 1);

        $keyboard_obj = new Keyboard([
            'resize_keyboard'   => $resize_keyboard,
            'one_time_keyboard' => $one_time_keyboard,
            'selective'         => $selective,
            'keyboard'          => [['Button Text 1']],
        ]);

        $keyboard = $keyboard_obj->getProperty('keyboard');
        self::assertSame('Button Text 1', $keyboard[0][0]->getText());

        self::assertSame($resize_keyboard, $keyboard_obj->getResizeKeyboard());
        self::assertSame($one_time_keyboard, $keyboard_obj->getOneTimeKeyboard());
        self::assertSame($selective, $keyboard_obj->getSelective());
    }

    public function testPredefinedKeyboards()
    {
        $keyboard_remove = Keyboard::remove();
        self::assertTrue($keyboard_remove->getProperty('remove_keyboard'));

        $keyboard_force_reply = Keyboard::forceReply();
        self::assertTrue($keyboard_force_reply->getProperty('force_reply'));
    }

    public function testKeyboardMethods()
    {
        $keyboard_obj = new Keyboard([]);

        self::assertEmpty($keyboard_obj->getOneTimeKeyboard());
        self::assertEmpty($keyboard_obj->getResizeKeyboard());
        self::assertEmpty($keyboard_obj->getSelective());

        $keyboard_obj->setOneTimeKeyboard(true);
        self::assertTrue($keyboard_obj->getOneTimeKeyboard());
        $keyboard_obj->setOneTimeKeyboard(false);
        self::assertFalse($keyboard_obj->getOneTimeKeyboard());

        $keyboard_obj->setResizeKeyboard(true);
        self::assertTrue($keyboard_obj->getResizeKeyboard());
        $keyboard_obj->setResizeKeyboard(false);
        self::assertFalse($keyboard_obj->getResizeKeyboard());

        $keyboard_obj->setSelective(true);
        self::assertTrue($keyboard_obj->getSelective());
        $keyboard_obj->setSelective(false);
        self::assertFalse($keyboard_obj->getSelective());
    }

    public function testKeyboardAddRows()
    {
        $keyboard_obj = new Keyboard([]);

        $keyboard_obj->addRow('Button Text 1');
        $keyboard = $keyboard_obj->getProperty('keyboard');
        self::assertSame('Button Text 1', $keyboard[0][0]->getText());

        $keyboard_obj->addRow('Button Text 2', 'Button Text 3');
        $keyboard = $keyboard_obj->getProperty('keyboard');
        self::assertSame('Button Text 2', $keyboard[1][0]->getText());
        self::assertSame('Button Text 3', $keyboard[1][1]->getText());

        $keyboard_obj->addRow(['text' => 'Button Text 4']);
        $keyboard = $keyboard_obj->getProperty('keyboard');
        self::assertSame('Button Text 4', $keyboard[2][0]->getText());
    }
}
