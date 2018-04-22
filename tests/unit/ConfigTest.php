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

class ConfigTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function get_config_array()
    {
        $configData = [
            'locales' => [
                'ka' => [
                    'name' => 'Georgian',
                ],
            ],
        ];
        $config = $this->getConfig($configData);

        $this->assertEquals($configData, $config->get());
    }

    /**
     * @test
     */
    public function set_get()
    {
        $config = [
            'locales' => [
                'ka' => [
                    'name' => 'Georgian',
                ],
            ],
        ];
        $config = $this->getConfig($config);

        $this->assertEquals('Georgian', $config->get('locales.ka.name'));
    }

    /**
     * @test
     */
    public function get_default_value()
    {
        $config = [
            'locales' => [
                'ka' => [
                    'name' => 'Georgian',
                ],
            ],
        ];
        $config = $this->getConfig($config);

        $this->assertEquals('Thai', $config->get('locales.th.name', 'Thai'));
    }

    /**
     * @test
     */
    public function table_name()
    {
        $config = [
            'db' => [
                'autosave' => true,
                'connection' => 'mysql',
                'texts_table' => 'texts',
            ],
        ];

        $config = $this->getConfig($config);

        $this->assertEquals('texts', $config->get('db.texts_table'));
    }
}
