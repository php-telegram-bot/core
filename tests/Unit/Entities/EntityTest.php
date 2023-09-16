<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Tests\Unit\Entities;

use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Tests\Unit\TestCase;

/**
 * @link            https://github.com/php-telegram-bot/core
 * @author          Baev Nikolay <gametester3d@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @package         TelegramTest
 */
class EntityTest extends TestCase
{
    public function testEscapeMarkdown(): void
    {
        // Make sure all characters that need escaping are escaped.

        // Markdown V1
        self::assertEquals('\[\`\*\_', Entity::escapeMarkdown('[`*_'));
        self::assertEquals('\*mark\*\_down\_~test~', Entity::escapeMarkdown('*mark*_down_~test~'));

        // Markdown V2
        self::assertEquals('\_\*\[\]\(\)\~\`\>\#\+\-\=\|\{\}\.\!', Entity::escapeMarkdownV2('_*[]()~`>#+-=|{}.!'));
        self::assertEquals('\*mark\*\_down\_\~test\~', Entity::escapeMarkdownV2('*mark*_down_~test~'));
    }

    public function testSettingDynamicParameterWorks(): void
    {
        $entity = new class ( [] ) extends Entity { }; // phpcs:ignore

        $entity->newParameter = 'test';

        $this->assertEquals('test', $entity->newParameter);
    }

    public function testGettingUnknownDynamicParameterReturnsNull(): void
    {
        $entity = new class ( [] ) extends Entity { }; // phpcs:ignore

        $this->assertNull($entity->unknownParameter);
    }
}
