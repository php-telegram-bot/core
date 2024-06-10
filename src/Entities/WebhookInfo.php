<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;

/**
 * @method string        getUrl()                          Webhook URL, may be empty if webhook is not set up
 * @method bool          hasCustomCertificate()            True, if a custom certificate was provided for webhook certificate checks
 * @method int           getPendingUpdateCount()           Number of updates awaiting delivery
 * @method string|null   getIpAddress()                    Optional. Currently used webhook IP address
 * @method int|null      getLastErrorDate()                Optional. Unix time for the most recent error that happened when trying to deliver an update via webhook
 * @method string|null   getLastErrorMessage()             Optional. Unix time for the most recent error that happened when trying to deliver an update via webhook
 * @method int|null      getLastSynchronizationErrorDate() Optional. Unix time of the most recent error that happened when trying to synchronize available updates with Telegram datacenters
 * @method int|null      getMaxConnections()               Optional. The maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery
 * @method string[]|null getAllowedUpdates()               Optional. A list of update types the bot is subscribed to. Defaults to all update types except chat_member
 */
class WebhookInfo extends Entity implements AllowsBypassingGet
{
    //
    public static function fieldsBypassingGet(): array
    {
        return [
            'has_custom_certificate' => false,
        ];
    }
}
