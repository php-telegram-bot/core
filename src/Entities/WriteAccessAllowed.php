<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method bool|null   getFromRequest()        Optional. True, if the access was granted after the user accepted an explicit request from a Web App sent by the method requestWriteAccess
 * @method string|null getWebAppName()         Optional. Name of the Web App, if the access was granted when the Web App was launched from a link
 * @method bool|null   getFromAttachmentMenu() Optional. True, if the access was granted when the bot was added to the attachment or side menu
 */
class WriteAccessAllowed extends Entity
{
    //
}
