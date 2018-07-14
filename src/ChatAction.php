<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot;

class ChatAction
{
    /**
     * Typing chat action
     */
    const TYPING = 'typing';

    /**
     * Upload Photo chat action
     */
    const UPLOAD_PHOTO = 'upload_photo';

    /**
     * Record Video chat action
     */
    const RECORD_VIDEO = 'record_video';

    /**
     * Upload Video chat action
     */
    const UPLOAD_VIDEO = 'upload_video';

    /**
     * Record Audio chat action
     */
    const RECORD_AUDIO = 'record_audio';

    /**
     * Upload Audio chat action
     */
    const UPLOAD_AUDIO = 'upload_audio';

    /**
     * Upload Document chat action
     */
    const UPLOAD_DOCUMENT = 'upload_document';

    /**
     * Find Location chat action
     */
    const FIND_LOCATION = 'find_location';

    /**
     * Record Video Note chat action
     */
    const RECORD_VIDEO_NOTE = 'record_video_note';

    /**
     * Upload Video note chat action
     */
    const UPLOAD_VIDEO_NOTE = 'upload_video_note';
}
