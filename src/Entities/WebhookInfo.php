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

class WebhookInfo extends Entity
{
    protected $url;                     //  String	    Webhook URL, may be empty if webhook is not set up
    protected $has_custom_certificate;  //  Boolean	    True, if a custom certificate was provided for webhook certificate checks
    protected $pending_update_count;    //  Integer	    Number of updates awaiting delivery
    protected $last_error_date;         //  Integer     Optional. Unix time for the most recent error that happened when trying to deliver an update via webhook
    protected $last_error_message;      //  String	    Optional. Error message in human-readable format for the most recent error that happened when trying to deliver an update via webhook

    public function __construct(array $data)
    {
        $this->url                    = isset($data['url']) ? $data['url'] : null;
        $this->has_custom_certificate = isset($data['has_custom_certificate']) ? $data['has_custom_certificate'] : null;
        $this->pending_update_count   = isset($data['pending_update_count']) ? $data['pending_update_count'] : null;
        $this->last_error_date        = isset($data['last_error_date']) ? $data['last_error_date'] : null;
        $this->last_error_message     = isset($data['last_error_message']) ? $data['last_error_message'] : null;
    }

    /**
     * Webhook URL, may be empty if webhook is not set up.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * True, if a custom certificate was provided for webhook certificate checks.
     *
     * @return bool
     */
    public function getHasCustomCertificate()
    {
        return $this->has_custom_certificate;
    }

    /**
     * Number of updates awaiting delivery.
     *
     * @return int
     */
    public function getPendingUpdateCount()
    {
        return $this->pending_update_count;
    }

    /**
     * Optional.
     * Unix time for the most recent error that happened when trying to deliver an update via webhook.
     *
     * @return int
     */
    public function getLastErrorDate()
    {
        return $this->last_error_date;
    }

    /**
     * Optional.
     * Error message in human-readable format for the most recent error that happened when trying to deliver an update via webhook.
     *
     * @return string
     */
    public function getLastErrorMessage()
    {
        return $this->last_error_message;
    }
}
