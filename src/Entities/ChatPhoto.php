<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string getSmallFileId()       File identifier of small (160x160), chat photo. This file_id can be used only for photo download and only for as long as the photo is not changed.
 * @method string getSmallFileUniqueId() Unique file identifier of small (160x160), chat photo, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method string getBigFileId()         File identifier of big (640x640), chat photo. This file_id can be used only for photo download and only for as long as the photo is not changed.
 * @method string getBigFileUniqueId()   Unique file identifier of big (640x640), chat photo, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 */
class ChatPhoto extends Entity
{
    //
}
