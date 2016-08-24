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

use Longman\TelegramBot\Exception\TelegramException;

class Update extends Entity
{
    /**
     * @var mixed|null
     */
    protected $update_id;

    /**
     * @var \Longman\TelegramBot\Entities\Message
     */
    protected $message;

    /**
     * @var \Longman\TelegramBot\Entities\Message
     */
    protected $edited_message;

    /**
     * @var \Longman\TelegramBot\Entities\InlineQuery
     */
    protected $inline_query;

    /**
     * @var \Longman\TelegramBot\Entities\ChosenInlineResult
     */
    protected $chosen_inline_result;

    /**
     * @var \Longman\TelegramBot\Entities\CallbackQuery
     */
    protected $callback_query;

    /**
     * @var string
     */
    private $update_type;

    /**
     * Update constructor.
     *
     * @param array $data
     * @param       $bot_name
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data, $bot_name)
    {

        $this->bot_name = $bot_name;

        $update_id       = isset($data['update_id']) ? $data['update_id'] : null;
        $this->update_id = $update_id;

        $this->message = isset($data['message']) ? $data['message'] : null;
        if (!empty($this->message)) {
            $this->message     = new Message($this->message, $bot_name);
            $this->update_type = 'message';
        }

        $this->edited_message = isset($data['edited_message']) ? $data['edited_message'] : null;
        if (!empty($this->edited_message)) {
            $this->edited_message = new Message($this->edited_message, $bot_name);
            $this->update_type    = 'edited_message';
        }

        if (empty($update_id)) {
            throw new TelegramException('update_id is empty!');
        }

        $this->inline_query = isset($data['inline_query']) ? $data['inline_query'] : null;
        if (!empty($this->inline_query)) {
            $this->inline_query = new InlineQuery($this->inline_query);
            $this->update_type  = 'inline_query';
        }

        $this->chosen_inline_result = isset($data['chosen_inline_result']) ? $data['chosen_inline_result'] : null;
        if (!empty($this->chosen_inline_result)) {
            $this->chosen_inline_result = new ChosenInlineResult($this->chosen_inline_result);
            $this->update_type          = 'chosen_inline_result';
        }

        $this->callback_query = isset($data['callback_query']) ? $data['callback_query'] : null;
        if (!empty($this->callback_query)) {
            $this->callback_query = new CallbackQuery($this->callback_query);
            $this->update_type    = 'callback_query';
        }
    }

    /**
     * Get update id
     *
     * @return mixed|null
     */
    public function getUpdateId()
    {
        return $this->update_id;
    }

    /**
     * Get message
     *
     * @return \Longman\TelegramBot\Entities\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get edited message
     *
     * @return \Longman\TelegramBot\Entities\Message
     */
    public function getEditedMessage()
    {
        return $this->edited_message;
    }

    /**
     * Get inline query
     *
     * @return \Longman\TelegramBot\Entities\InlineQuery
     */
    public function getInlineQuery()
    {
        return $this->inline_query;
    }

    /**
     * Get callback query
     *
     * @return \Longman\TelegramBot\Entities\CallbackQuery
     */
    public function getCallbackQuery()
    {
        return $this->callback_query;
    }

    /**
     * Get chosen inline result
     *
     * @return \Longman\TelegramBot\Entities\ChosenInlineResult
     */
    public function getChosenInlineResult()
    {
        return $this->chosen_inline_result;
    }

    /**
     * Get update type
     *
     * @return string
     */
    public function getUpdateType()
    {
        return $this->update_type;
    }

    /**
     * Get update content
     *
     * @return \Longman\TelegramBot\Entities\CallbackQuery
     *          |\Longman\TelegramBot\Entities\ChosenInlineResult
     *          |\Longman\TelegramBot\Entities\InlineQuery
     *          |\Longman\TelegramBot\Entities\Message
     */
    public function getUpdateContent()
    {
        if ($this->update_type == 'message') {
            return $this->getMessage();
        } elseif ($this->update_type == 'inline_query') {
            return $this->getInlineQuery();
        } elseif ($this->update_type == 'chosen_inline_result') {
            return $this->getChosenInlineResult();
        } elseif ($this->update_type == 'callback_query') {
            return $this->getCallbackQuery();
        }
    }
}
