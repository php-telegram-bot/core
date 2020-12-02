<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Tests\Unit;

use Longman\TelegramBot\Entities\WebhookInfo;

/**
 * @package         TelegramTest
 * @author          Baev Nikolay <gametester3d@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class WebhookInfoTest extends TestCase
{
    /**
     * @var array Webhook data
     */
    public $data;

    public function setUp(): void
    {
        $this->data = [
            'url'                    => 'http://phpunit',
            'has_custom_certificate' => (bool) mt_rand(0, 1),
            'pending_update_count'   => (int) mt_rand(1, 9),
            'ip_address'             => '1.2.3.4',
            'last_error_date'        => time(),
            'last_error_message'     => 'Some_error_message',
            'max_connections'        => (int) mt_rand(1, 100),
            'allowed_updates'        => ['message', 'edited_channel_post', 'callback_query'],
        ];
    }

    public function testBaseStageWebhookInfo(): void
    {
        $webhook = new WebhookInfo($this->data);
        self::assertInstanceOf(WebhookInfo::class, $webhook);
    }

    public function testGetUrl(): void
    {
        $webhook = new WebhookInfo($this->data);
        $url     = $webhook->getUrl();
        self::assertEquals($this->data['url'], $url);
    }

    public function testGetHasCustomCertificate(): void
    {
        $webhook            = new WebhookInfo($this->data);
        $custom_certificate = $webhook->getHasCustomCertificate();
        self::assertIsBool($custom_certificate);
        self::assertEquals($this->data['has_custom_certificate'], $custom_certificate);
    }

    public function testGetPendingUpdateCount(): void
    {
        $webhook      = new WebhookInfo($this->data);
        $update_count = $webhook->getPendingUpdateCount();
        self::assertIsInt($update_count);
        self::assertEquals($this->data['pending_update_count'], $update_count);
    }

    public function testGetIpAddress(): void
    {
        $webhook    = new WebhookInfo($this->data);
        $ip_address = $webhook->getIpAddress();
        self::assertIsString($ip_address);
        self::assertEquals($this->data['ip_address'], $ip_address);
    }

    public function testGetLastErrorDate(): void
    {
        $webhook    = new WebhookInfo($this->data);
        $error_date = $webhook->getLastErrorDate();
        self::assertIsInt($error_date);
        self::assertEquals($this->data['last_error_date'], $error_date);
    }

    public function testGetLastErrorMessage(): void
    {
        $webhook   = new WebhookInfo($this->data);
        $error_msg = $webhook->getLastErrorMessage();
        self::assertIsString($error_msg);
        self::assertEquals($this->data['last_error_message'], $error_msg);
    }

    public function testGetMaxConnections(): void
    {
        $webhook         = new WebhookInfo($this->data);
        $max_connections = $webhook->getMaxConnections();
        self::assertIsInt($max_connections);
        self::assertEquals($this->data['max_connections'], $max_connections);
    }

    public function testGetAllowedUpdates(): void
    {
        $webhook         = new WebhookInfo($this->data);
        $allowed_updates = $webhook->getAllowedUpdates();
        self::assertIsArray($allowed_updates);
        self::assertEquals($this->data['allowed_updates'], $allowed_updates);
    }

    public function testGetDataWithoutParams(): void
    {
        // Make a copy to not risk failed tests if not run in proper order.
        $data = $this->data;

        unset($data['url']);
        self::assertNull((new WebhookInfo($data))->getUrl());

        unset($data['has_custom_certificate']);
        self::assertNull((new WebhookInfo($data))->getHasCustomCertificate());

        unset($data['pending_update_count']);
        self::assertNull((new WebhookInfo($data))->getPendingUpdateCount());

        unset($data['ip_address']);
        self::assertNull((new WebhookInfo($data))->getIpAddress());

        unset($data['last_error_date']);
        self::assertNull((new WebhookInfo($data))->getLastErrorDate());

        unset($data['last_error_message']);
        self::assertNull((new WebhookInfo($data))->getLastErrorMessage());

        unset($data['max_connections']);
        self::assertNull((new WebhookInfo($data))->getMaxConnections());

        unset($data['allowed_updates']);
        self::assertNull((new WebhookInfo($data))->getAllowedUpdates());
    }
}
