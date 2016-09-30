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

class InlineQueryResultDocument extends InlineQueryResult
{
    /**
     * @var mixed|null
     */
    protected $title;

    /**
     * @var mixed|null
     */
    protected $caption;

    /**
     * @var mixed|null
     */
    protected $document_url;

    /**
     * @var mixed|null
     */
    protected $mime_type;

    /**
     * @var mixed|null
     */
    protected $description;

    /**
     * @var mixed|null
     */
    protected $thumb_url;

    /**
     * @var mixed|null
     */
    protected $thumb_width;

    /**
     * @var mixed|null
     */
    protected $thumb_height;

    /**
     * InlineQueryResultDocument constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'document';

        $this->title = isset($data['title']) ? $data['title'] : null;
        if (empty($this->title)) {
            throw new TelegramException('title is empty!');
        }

        $this->caption = isset($data['caption']) ? $data['caption'] : null;

        $this->document_url = isset($data['document_url']) ? $data['document_url'] : null;
        if (empty($this->document_url)) {
            throw new TelegramException('document_url is empty!');
        }

        $this->mime_type = isset($data['mime_type']) ? $data['mime_type'] : null;
        if (empty($this->mime_type)) {
            throw new TelegramException('mime_type is empty!');
        }

        $this->description  = isset($data['description']) ? $data['description'] : null;
        $this->thumb_url    = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        $this->thumb_width  = isset($data['thumb_width']) ? $data['thumb_width'] : null;
        $this->thumb_height = isset($data['thumb_height']) ? $data['thumb_height'] : null;
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

    /**
     * Get document url
     *
     * @return mixed|null
     */
    public function getDocumentUrl()
    {
        return $this->document_url;
    }

    /**
     * Get mime type
     *
     * @return mixed|null
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * Get description
     *
     * @return mixed|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get thumb url
     *
     * @return mixed|null
     */
    public function getThumbUrl()
    {
        return $this->thumb_url;
    }

    /**
     * Get thumb width
     *
     * @return mixed|null
     */
    public function getThumbWidth()
    {
        return $this->thumb_width;
    }

    /**
     * Get thumb height
     *
     * @return mixed|null
     */
    public function getThumbHeight()
    {
        return $this->thumb_height;
    }
}
