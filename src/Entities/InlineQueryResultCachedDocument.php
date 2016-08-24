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
    /**
     * @var mixed|null
     */
    protected $document_file_id;

    /**
     * @var mixed|null
     */
    protected $title;

    /**
     * @var mixed|null
     */
    protected $description;

    /**
     * @var mixed|null
     */
    protected $caption;

    /**
     * InlineQueryResultCachedDocument constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
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
        $this->caption     = isset($data['caption']) ? $data['caption'] : null;
    }

    /**
     * Get document file id
     *
     * @return mixed|null
     */
    public function getDocumentFileId()
    {
        return $this->document_file_id;
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
     * Get description
     *
     * @return mixed|null
     */
    public function getDescription()
    {
        return $this->description;
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
