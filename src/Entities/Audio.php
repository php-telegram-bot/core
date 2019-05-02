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
 * Class Audio
 *
 * @link https://core.telegram.org/bots/api#audio
 *
 * @method string    getFileId()    Unique identifier for this file
 * @method int       getDuration()  Duration of the audio in seconds as defined by sender
 * @method string    getPerformer() Optional. Performer of the audio as defined by sender or by audio tags
 * @method string    getTitle()     Optional. Title of the audio as defined by sender or by audio tags
 * @method string    getMimeType()  Optional. MIME type of the file as defined by sender
 * @method int       getFileSize()  Optional. File size
 * @method PhotoSize getThumb()     Optional. Thumbnail of the album cover to which the music file belongs
 */
class Audio extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'thumb' => PhotoSize::class,
        ];
    }
}
