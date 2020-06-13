<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities\Games;

use Longman\TelegramBot\Entities\Animation;
use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\MessageEntity;
use Longman\TelegramBot\Entities\PhotoSize;

/**
 * Class Game
 *
 * This object represents a game. Use BotFather to create and edit games, their short names will act as unique identifiers.
 *
 * @link https://core.telegram.org/bots/api#game
 *
 * @method string          getTitle()        Title of the game
 * @method string          getDescription()  Description of the game
 * @method PhotoSize[]     getPhoto()        Photo that will be displayed in the game message in chats.
 * @method string          getText()         Optional. Brief description of the game or high scores included in the game message. Can be automatically edited to include current high scores for the game when the bot calls setGameScore, or manually edited using editMessageText. 0-4096 characters.
 * @method MessageEntity[] getTextEntities() Optional. Special entities that appear in text, such as usernames, URLs, bot commands, etc.
 * @method Animation       getAnimation()    Optional. Animation that will be displayed in the game message in chats. Upload via BotFather
 **/
class Game extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'photo'         => [PhotoSize::class],
            'text_entities' => [MessageEntity::class],
            'animation'     => Animation::class,
        ];
    }
}
