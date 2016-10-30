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
use Longman\TelegramBot\Tests\Unit\TestHelpers;

/**
 * @package         TelegramTest
 * @author          Baev Nikolay <gametester3d@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class WebhookInfoTest extends TestCase
{
    
   /**
    * webhook data
    *
    * @var array
    *
    */
    public $data;

   /**
    *
    * Set Up
    *
    */
    public function setUp()
    {
        $this->data = [
            'url'   => 'http://phpunit',
            'has_custom_certificate' => (bool)mt_rand(0, 1),
            'pending_update_count'   => (int)mt_rand(1, 9),
            'last_error_date'        => time(),
            'last_error_message'     => 'Same_error_message'
        ];
    }

   /**
    *
    * TearDown 
    *
    */
    public function tearDown()
    {
        //pass
    }

   /**
    *
    * Testing base stage with data object creating
    *
    */
    public function testBaseStageWebhookInfo()
    {
        $webhook = new WebhookInfo($this->data);
        $this->assertInstanceOf('Longman\TelegramBot\Entities\WebhookInfo', $webhook);
    }

    /**
    *
    * Testing getUrl
    *
    */
    public function testGetUrl()
    {
        $webhook = new WebhookInfo($this->data);
        $url = $webhook->getUrl();
        $this->assertEquals($this->data['url'], $url);
    }
    
   /**
    *
    * Testing getHasCustomCertificate
    *
    */
    public function testGetHasCustomCertificate()
    {
        $webhook = new WebhookInfo($this->data);
        $custom_certificate = $webhook->getHasCustomCertificate();
        $this->assertInternalType('bool', $custom_certificate);
        $this->assertEquals($this->data['has_custom_certificate'], $custom_certificate);
    }

   /**
    *
    * Testing getPendingUpdateCount
    *
    */
    public function testGetPendingUpdateCount()
    {
        $webhook = new WebhookInfo($this->data);
        $update_count = $webhook->getPendingUpdateCount();
        $this->assertInternalType('int', $update_count);
        $this->assertEquals($this->data['pending_update_count'], $update_count);
    } 

   /**
    *
    * Testing getLastErrorDate
    *
    */
    public function testGetLastErrorDate()
    {
        $webhook = new WebhookInfo($this->data);
        $error_date = $webhook->getLastErrorDate();
        $this->assertInternalType('int', $error_date);
        #$this->assertRegExp('/([0-9]{10,})/', $error_date);
        $this->assertEquals($this->data['last_error_date'], $error_date);
    }

    /**
    *
    * Testing getLastErrorMessage
    *
    */
    public function testGetLastErrorMessage()
    {
        $webhook = new WebhookInfo($this->data);
        $error_msg = $webhook->getLastErrorMessage();
        $this->assertInternalType('string', $error_msg, $error_msg);
        $this->assertEquals($this->data['last_error_message'], $error_msg);
    }

    /**
    * 
    * Testing get data without params
    *
    */
    public function testGetDataWithoutParams()
    {
        unset($this->data['url']);
        $webhook = new WebhookInfo($this->data);
        $result = $webhook->getUrl();
        $this->assertNull($result);

        unset($webhook, $result);

        unset($this->data['has_custom_certificate']);        
        $webhook = new WebhookInfo($this->data);
        $result = $webhook->getHasCustomCertificate();
        $this->assertNull($result);

        unset($webhook, $result);

        unset($this->data['pending_update_count']);
        $webhook = new WebhookInfo($this->data);
        $result = $webhook->getPendingUpdateCount();
        $this->assertNull($result);
        
        unset($webhook, $result);

        unset($this->data['last_error_date']);
        $webhook = new WebhookInfo($this->data);
        $result = $webhook->getLastErrorDate();
        $this->assertNull($result);

        unset($webhook, $result);

        unset($this->data['last_error_message']);
        $webhook = new WebhookInfo($this->data);
        $result = $webhook->getLastErrorMessage();
        $this->assertNull($result);
    }
}
