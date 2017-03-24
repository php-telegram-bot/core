<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

interface InlineKeyboardPaginator
{
    /**
     * A unique identifier for the callback query.
     *
     * @return string
     */
    public static function getCallbackDataId();

    /**
     * Get the output for the currently selected page.
     *
     * @param int $current_page
     *
     * @return string
     */
    public static function getOutput($current_page);

    /**
     * Get the pagination for the current page.
     *
     * @param int $current_page
     *
     * @return InlineKeyboard
     */
    public static function getPagination($current_page);
}
