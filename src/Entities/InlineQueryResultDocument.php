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
    protected $title;
    protected $caption;
    protected $document_url;
    protected $mime_type;
    protected $description;
    protected $thumb_url;
    protected $thumb_width;
    protected $thumb_height;

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

        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->thumb_url = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        $this->thumb_width = isset($data['thumb_width']) ? $data['thumb_width'] : null;
        $this->thumb_height = isset($data['thumb_height']) ? $data['thumb_height'] : null;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function getDocumentUrl()
    {
        return $this->document_url;
    }

    public function getMimeType()
    {
        return $this->mime_type;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getThumbUrl()
    {
        return $this->thumb_url;
    }

    public function getThumbWidth()
    {
        return $this->thumb_width;
    }

    public function getThumbHeight()
    {
        return $this->thumb_height;
    }
}
