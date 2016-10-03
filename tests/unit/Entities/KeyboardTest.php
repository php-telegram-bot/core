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
}
