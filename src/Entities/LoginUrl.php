<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string      getUrl()                An HTTPS URL to be opened with user authorization data added to the query string when the button is pressed. If the user refuses to provide authorization data, the original URL without information about the user will be opened. The data added is the same as described in Receiving authorization data.
 * @method string|null getForwardText()        Optional. New text of the button in forwarded messages.
 * @method string|null getBotUsername()        Optional. Username of a bot, which will be used for user authorization. See Setting up a bot for more details. If not specified, the current bot's username will be assumed. The url's domain must be the same as the domain linked with the bot. See Linking your domain to the bot for more details.
 * @method bool|null   getRequestWriteAccess() Optional. Pass True to request the permission for your bot to send messages to the user.
 */
class LoginUrl extends Entity
{
    //
}
