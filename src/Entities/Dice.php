<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string getEmoji() Emoji on which the dice throw animation is based
 * @method int    getValue() Value of the dice, 1-6 for “🎲”, “🎯” and “🎳” base emoji, 1-5 for “🏀” and “⚽” base emoji, 1-64 for “🎰” base emoji
 */
class Dice extends Entity
{
    public const EMOJI_DICE = '🎲';

    public const EMOJI_DARTS = '🎯';

    public const EMOJI_BOWLING = '🎳';

    public const EMOJI_BASKETBALL = '🏀';

    public const EMOJI_SOCCER = '⚽';

    public const EMOJI_SLOT_MACHINE = '🎰';
}
