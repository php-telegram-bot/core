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
            ['Button Text 1'],
            ['Button Text 2'],
            ['Button Text 3']
        ))->getProperty('keyboard');
        self::assertSame('Button Text 1', $keyboard[0][0]->getText());
        self::assertSame('Button Text 2', $keyboard[1][0]->getText());
        self::assertSame('Button Text 3', $keyboard[2][0]->getText());
    }

    public function testKeyboardMultipleButtonsSingleRow()
    {
        /** @var KeyboardButton $keyboard_button_1 */
        /** @var KeyboardButton $keyboard_button_2 */

        $keyboard = (new Keyboard('Button Text 1', 'Button Text 2'))->getProperty('keyboard');
        list($keyboard_button_1, $keyboard_button_2) = $keyboard[0]; // Row 1.
        self::assertSame('Button Text 1', $keyboard_button_1->getText());
        self::assertSame('Button Text 2', $keyboard_button_2->getText());

        $keyboard = (new Keyboard(['Button Text 3', 'Button Text 4']))->getProperty('keyboard');
        list($keyboard_button_1, $keyboard_button_2) = $keyboard[0]; // Row 1.
        self::assertSame('Button Text 3', $keyboard_button_1->getText());
        self::assertSame('Button Text 4', $keyboard_button_2->getText());
    }

    public function testKeyboardMultipleButtonsMultipleRows()
    {
        /** @var KeyboardButton $keyboard_button_1 */
        /** @var KeyboardButton $keyboard_button_2 */
        /** @var KeyboardButton $keyboard_button_3 */
        /** @var KeyboardButton $keyboard_button_4 */

        $keyboard = (new Keyboard(
            ['Button Text 1', 'Button Text 2'],
            ['Button Text 3', 'Button Text 4']
        ))->getProperty('keyboard');

        list($keyboard_button_1, $keyboard_button_2) = $keyboard[0]; // Row 1.
        list($keyboard_button_3, $keyboard_button_4) = $keyboard[1]; // Row 2.

        self::assertSame('Button Text 1', $keyboard_button_1->getText());
        self::assertSame('Button Text 2', $keyboard_button_2->getText());
        self::assertSame('Button Text 3', $keyboard_button_3->getText());
        self::assertSame('Button Text 4', $keyboard_button_4->getText());
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
        self::assertSame('Button Text 3', $keyboard[0][1]->getText());

        $keyboard = (new Keyboard(
            [new KeyboardButton('Button Text 4')],
            [new KeyboardButton('Button Text 5'), new KeyboardButton('Button Text 6')]
        ))->getProperty('keyboard');
        self::assertSame('Button Text 4', $keyboard[0][0]->getText());
        self::assertSame('Button Text 5', $keyboard[1][0]->getText());
        self::assertSame('Button Text 6', $keyboard[1][1]->getText());
    }


    /*public function testKeyboardWithData()
    {
        $keyboard = (new Keyboard(
            ['Button Text 1', 'Button Text 2'],
            ['Button Text 3', 'Button Text 4']
        ))->getProperty('keyboard');

    }*/

    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     * @expectedExceptionMessage You must use only one of these fields: request_contact, request_location!
     */
    /*public function testKeyboardTooManyParametersFail()
    {
        new Keyboard(['text' => 'message', 'request_contact' => true, 'request_location' => true]);
    }*/

    /*public function testKeyboardSuccess()
    {
        new Keyboard(['text' => 'message']);
        new Keyboard(['text' => 'message', 'request_contact' => true]);
        new Keyboard(['text' => 'message', 'request_location' => true]);
    }*/
}
