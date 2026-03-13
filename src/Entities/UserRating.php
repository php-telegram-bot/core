<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class UserRating
 *
 * This object describes the rating of a user based on their Telegram Star spendings.
 *
 * @link https://core.telegram.org/bots/api#userrating
 *
 * @method int getLevel()              Current level of the user, indicating their reliability when purchasing digital goods and services. A higher level suggests a more trustworthy customer; a negative level is likely reason for concern.
 * @method int getRating()             Numerical value of the user's rating; the higher the rating, the better
 * @method int getCurrentLevelRating() The rating value required to get the current level
 * @method int getNextLevelRating()    Optional. The rating value required to get to the next level; omitted if the maximum level was reached
 */
class UserRating extends Entity
{
}
