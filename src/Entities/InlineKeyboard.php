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

/**
 * Class InlineKeyboard
 *
 * @link https://core.telegram.org/bots/api#inlinekeyboardmarkup
 */
class InlineKeyboard extends Keyboard
{
    /**
     * Get an inline pagination keyboard.
     *
     * - $callback_data is an ID for the CallbackqueryCommand, to know where the request comes from.
     * The ID is automatically appended with '_page_%d' to filter out the selected page number.
     *
     * - $labels allows for custom button labels, using '%d' placeholders.
     * Default:
     * ```
     * [
     *     'first'    => '« %d',
     *     'previous' => '‹ %d',
     *     'current'  => '· %d ·',
     *     'next'     => '%d ›',
     *     'last'     => '%d »',
     * ]
     * ``
     *`
     * initial idea from: https://stackoverflow.com/a/42879866
     *
     * @param string $callback_data
     * @param int    $current_page
     * @param int    $max_pages
     * @param array  $labels
     *
     * @return \Longman\TelegramBot\Entities\InlineKeyboard
     */
    public static function getPagination($callback_data, $current_page, $max_pages, array $labels = [])
    {
        $callback_data .= '_page_%d';

        // Merge labels with defaults.
        $labels = array_merge([
            'first'    => '« %d',
            'previous' => '‹ %d',
            'current'  => '· %d ·',
            'next'     => '%d ›',
            'last'     => '%d »',
        ], $labels);
        $pages  = [
            'first'    => 1,
            'previous' => $current_page - 1,
            'current'  => $current_page,
            'next'     => $current_page + 1,
            'last'     => $max_pages,
        ];

        // Set labels for keyboard, replacing placeholders with page numbers.
        foreach ($labels as $key => &$label) {
            if (strpos($label, '%d') !== false) {
                $label = sprintf($label, $pages[$key]);
            } elseif ($label === '') {
                $label = null;
            }
        }
        unset($label);

        $callbacks_data = [];
        foreach ($pages as $key => $page) {
            $callbacks_data[$key] = sprintf($callback_data, $page);
        }

        $buttons = [];

        if ($current_page > 1 && $labels['first'] !== null) {
            $buttons[] = new InlineKeyboardButton(['text' => $labels['first'], 'callback_data' => $callbacks_data['first']]);
        }
        if ($current_page > 2 && $labels['previous'] !== null) {
            $buttons[] = new InlineKeyboardButton(['text' => $labels['previous'], 'callback_data' => $callbacks_data['previous']]);
        }

        if ($labels['current'] !== null) {
            $buttons[] = new InlineKeyboardButton(['text' => $labels['current'], 'callback_data' => $callbacks_data['current']]);
        }

        if ($current_page < $max_pages - 1 && $labels['next'] !== null) {
            $buttons[] = new InlineKeyboardButton(['text' => $labels['next'], 'callback_data' => $callbacks_data['next']]);
        }
        if ($current_page < $max_pages && $labels['last'] !== null) {
            $buttons[] = new InlineKeyboardButton(['text' => $labels['last'], 'callback_data' => $callbacks_data['last']]);
        }

        return new InlineKeyboard($buttons);
    }

    /**
     * Extract the page number from the passed callback data.
     *
     * @param string $callback_data
     *
     * @return int
     */
    public static function getPageFromCallbackData($callback_data)
    {
        return (int) preg_replace('/.*_page_(\d+)$/', '$1', $callback_data);
    }
}
