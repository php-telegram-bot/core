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

    public function setUp()
    {
        $this->data = [
            'url'                    => 'http://phpunit',
            'has_custom_certificate' => (bool) mt_rand(0, 1),
            'pending_update_count'   => (int) mt_rand(1, 9),
            'last_error_date'        => time(),
            'last_error_message'     => 'Some_error_message',
            'max_connections'        => (int) mt_rand(1, 100),
            'allowed_updates'        => ['message', 'edited_channel_post', 'callback_query'],
        ];
    }

    public function testBaseStageWebhookInfo()
    {
        $webhook = new WebhookInfo($this->data);
        $this->assertInstanceOf('Longman\TelegramBot\Entities\WebhookInfo', $webhook);
    }

    public function testGetUrl()
    {
        $webhook = new WebhookInfo($this->data);
        $url     = $webhook->getUrl();
        $this->assertEquals($this->data['url'], $url);
    }

    public function testGetHasCustomCertificate()
    {
        $webhook            = new WebhookInfo($this->data);
        $custom_certificate = $webhook->getHasCustomCertificate();
        $this->assertInternalType('bool', $custom_certificate);
        $this->assertEquals($this->data['has_custom_certificate'], $custom_certificate);
    }

    public function testGetPendingUpdateCount()
    {
        $webhook      = new WebhookInfo($this->data);
        $update_count = $webhook->getPendingUpdateCount();
        $this->assertInternalType('int', $update_count);
        $this->assertEquals($this->data['pending_update_count'], $update_count);
    }

    public function testGetLastErrorDate()
    {
        $webhook    = new WebhookInfo($this->data);
        $error_date = $webhook->getLastErrorDate();
        $this->assertInternalType('int', $error_date);
        $this->assertEquals($this->data['last_error_date'], $error_date);
    }

    public function testGetLastErrorMessage()
    {
        $webhook   = new WebhookInfo($this->data);
        $error_msg = $webhook->getLastErrorMessage();
        $this->assertInternalType('string', $error_msg);
        $this->assertEquals($this->data['last_error_message'], $error_msg);
    }

    public function testGetMaxConnections()
    {
        $webhook         = new WebhookInfo($this->data);
        $max_connections = $webhook->getMaxConnections();
        $this->assertInternalType('int', $max_connections);
        $this->assertEquals($this->data['max_connections'], $max_connections);
    }

    public function testGetAllowedUpdates()
    {
        $webhook         = new WebhookInfo($this->data);
        $allowed_updates = $webhook->getAllowedUpdates();
        $this->assertInternalType('array', $allowed_updates);
        $this->assertEquals($this->data['allowed_updates'], $allowed_updates);
    }

    public function testGetDataWithoutParams()
    {
        // Make a copy to not risk failed tests if not run in proper order.
        $data = $this->data;

        unset($data['url']);
        $this->assertNull((new WebhookInfo($data))->getUrl());

        unset($data['has_custom_certificate']);
        $this->assertNull((new WebhookInfo($data))->getHasCustomCertificate());

        unset($data['pending_update_count']);
        $this->assertNull((new WebhookInfo($data))->getPendingUpdateCount());

        unset($data['last_error_date']);
        $this->assertNull((new WebhookInfo($data))->getLastErrorDate());

        unset($data['last_error_message']);
        $this->assertNull((new WebhookInfo($data))->getLastErrorMessage());

        unset($data['max_connections']);
        $this->assertNull((new WebhookInfo($data))->getMaxConnections());

        unset($data['allowed_updates']);
        $this->assertNull((new WebhookInfo($data))->getAllowedUpdates());
    }
}
