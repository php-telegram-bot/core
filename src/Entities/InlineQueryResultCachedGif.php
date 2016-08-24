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

class InlineQueryResultCachedGif extends InlineQueryResult
{
    /**
     * @var mixed|null
     */
    protected $gif_file_id;

    /**
     * @var mixed|null
     */
    protected $title;

    /**
     * @var
     */
    protected $description;

    /**
     * @var mixed|null
     */
    protected $caption;

    /**
     * InlineQueryResultCachedGif constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'gif';

        $this->gif_file_id = isset($data['gif_file_id']) ? $data['gif_file_id'] : null;
        if (empty($this->gif_file_id)) {
            throw new TelegramException('gif_file_id is empty!');
        }

        $this->title   = isset($data['title']) ? $data['title'] : null;
        $this->caption = isset($data['caption']) ? $data['caption'] : null;
    }

    /**
     * Get gif file id
     *
     * @return mixed|null
     */
    public function getGifFileId()
    {
        return $this->gif_file_id;
    }

    /**
     * Get title
     *
     * @return mixed|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get caption
     *
     * @return mixed|null
     */
    public function getCaption()
    {
        return $this->caption;
    }
}
