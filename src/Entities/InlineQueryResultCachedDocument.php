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

class InlineQueryResultCachedDocument extends InlineQueryResult
{
    protected $document_file_id;
    protected $title;
    protected $description;
    protected $caption;

    /**
     * InlineQueryResultCachedDocument constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'document';

        $this->document_file_id = isset($data['document_file_id']) ? $data['document_file_id'] : null;
        if (empty($this->document_file_id)) {
            throw new TelegramException('document_file_id is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        if (empty($this->title)) {
            throw new TelegramException('title is empty!');
        }

        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->caption = isset($data['caption']) ? $data['caption'] : null;
    }

    public function getDocumentFileId()
    {
        return $this->document_file_id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCaption()
    {
        return $this->caption;
    }
}
