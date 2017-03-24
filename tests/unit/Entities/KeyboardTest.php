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
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class KeyboardTest extends TestCase
{
    public static function assertButtonPropertiesEqual($value, $property, $keyboard, $row, $column, $message = '')
    {
        self::assertSame($value, $keyboard->getProperty($keyboard->getKeyboardType())[$row][$column]->getProperty($property), $message);
    }

    public static function assertRowButtonPropertiesEqual(array $values, $property, $keyboard, $row, $message = '')
    {
        $column = 0;
        foreach ($values as $value) {
            self::assertButtonPropertiesEqual($value, $property, $keyboard, $row, $column++, $message);
        }
    }

    public static function assertAllButtonPropertiesEqual(array $all_values, $property, $keyboard, $message = '')
    {
        $row = 0;
        foreach ($all_values as $values) {
            self::assertRowButtonPropertiesEqual($values, $property, $keyboard, $row++, $message);
        }
    }

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
        $keyboard = new Keyboard('Button Text 1');
        self::assertAllButtonPropertiesEqual([
            ['Button Text 1'],
        ], 'text', $keyboard);

        $keyboard = new Keyboard(['Button Text 2']);
        self::assertAllButtonPropertiesEqual([
            ['Button Text 2'],
        ], 'text', $keyboard);
    }

    public function testKeyboardSingleButtonMultipleRows()
    {
        $keyboard = new Keyboard(
            'Button Text 1',
            'Button Text 2',
            'Button Text 3'
        );
        self::assertAllButtonPropertiesEqual([
            ['Button Text 1'],
            ['Button Text 2'],
            ['Button Text 3'],
        ], 'text', $keyboard);

        $keyboard = new Keyboard(
            ['Button Text 4'],
            ['Button Text 5'],
            ['Button Text 6']
        );
        self::assertAllButtonPropertiesEqual([
            ['Button Text 4'],
            ['Button Text 5'],
            ['Button Text 6'],
        ], 'text', $keyboard);
    }

    public function testKeyboardMultipleButtonsSingleRow()
    {
        $keyboard = new Keyboard(['Button Text 1', 'Button Text 2']);
        self::assertAllButtonPropertiesEqual([
            ['Button Text 1', 'Button Text 2'],
        ], 'text', $keyboard);
    }

    public function testKeyboardMultipleButtonsMultipleRows()
    {
        $keyboard = new Keyboard(
            ['Button Text 1', 'Button Text 2'],
            ['Button Text 3', 'Button Text 4']
        );
        self::assertAllButtonPropertiesEqual([
            ['Button Text 1', 'Button Text 2'],
            ['Button Text 3', 'Button Text 4'],
        ], 'text', $keyboard);
    }

    public function testKeyboardWithButtonObjects()
    {
        $keyboard = new Keyboard(
            new KeyboardButton('Button Text 1')
        );
        self::assertAllButtonPropertiesEqual([
            ['Button Text 1'],
        ], 'text', $keyboard);

        $keyboard = new Keyboard(
            new KeyboardButton('Button Text 2'),
            new KeyboardButton('Button Text 3')
        );
        self::assertAllButtonPropertiesEqual([
            ['Button Text 2'],
            ['Button Text 3'],
        ], 'text', $keyboard);

        $keyboard = new Keyboard(
            [new KeyboardButton('Button Text 4')],
            [new KeyboardButton('Button Text 5'), new KeyboardButton('Button Text 6')]
        );
        self::assertAllButtonPropertiesEqual([
            ['Button Text 4'],
            ['Button Text 5', 'Button Text 6'],
        ], 'text', $keyboard);
    }

    public function testKeyboardWithDataArray()
    {
        $resize_keyboard   = (bool) mt_rand(0, 1);
        $one_time_keyboard = (bool) mt_rand(0, 1);
        $selective         = (bool) mt_rand(0, 1);

        $keyboard = new Keyboard([
            'resize_keyboard'   => $resize_keyboard,
            'one_time_keyboard' => $one_time_keyboard,
            'selective'         => $selective,
            'keyboard'          => [['Button Text 1']],
        ]);
        self::assertAllButtonPropertiesEqual([
            ['Button Text 1'],
        ], 'text', $keyboard);

        self::assertSame($resize_keyboard, $keyboard->getResizeKeyboard());
        self::assertSame($one_time_keyboard, $keyboard->getOneTimeKeyboard());
        self::assertSame($selective, $keyboard->getSelective());
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
        $keyboard = new Keyboard([]);

        self::assertEmpty($keyboard->getOneTimeKeyboard());
        self::assertEmpty($keyboard->getResizeKeyboard());
        self::assertEmpty($keyboard->getSelective());

        $keyboard->setOneTimeKeyboard(true);
        self::assertTrue($keyboard->getOneTimeKeyboard());
        $keyboard->setOneTimeKeyboard(false);
        self::assertFalse($keyboard->getOneTimeKeyboard());

        $keyboard->setResizeKeyboard(true);
        self::assertTrue($keyboard->getResizeKeyboard());
        $keyboard->setResizeKeyboard(false);
        self::assertFalse($keyboard->getResizeKeyboard());

        $keyboard->setSelective(true);
        self::assertTrue($keyboard->getSelective());
        $keyboard->setSelective(false);
        self::assertFalse($keyboard->getSelective());
    }

    public function testKeyboardAddRows()
    {
        $keyboard = new Keyboard([]);

        $keyboard->addRow('Button Text 1');
        self::assertAllButtonPropertiesEqual([
            ['Button Text 1'],
        ], 'text', $keyboard);

        $keyboard->addRow('Button Text 2', 'Button Text 3');
        self::assertAllButtonPropertiesEqual([
            ['Button Text 1'],
            ['Button Text 2', 'Button Text 3'],
        ], 'text', $keyboard);

        $keyboard->addRow(['text' => 'Button Text 4']);
        self::assertAllButtonPropertiesEqual([
            ['Button Text 1'],
            ['Button Text 2', 'Button Text 3'],
            ['Button Text 4'],
        ], 'text', $keyboard);
    }
}
