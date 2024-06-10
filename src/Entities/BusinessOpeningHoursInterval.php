<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method int getOpeningMinute() The minute's sequence number in a week, starting on Monday, marking the start of the time interval during which the business is open; 0 - 7 * 24 * 60
 * @method int getClosingMinute() The minute's sequence number in a week, starting on Monday, marking the end of the time interval during which the business is open; 0 - 8 * 24 * 60
 */
class BusinessOpeningHoursInterval extends Entity
{
    //
}
