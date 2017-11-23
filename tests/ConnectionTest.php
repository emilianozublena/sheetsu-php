<?php
/**
 * Unit test for Connection class
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu\Tests;


use Sheetsu\Connection;
use Sheetsu\Response;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider basicConfigProvider
     */
    public function testConstructSetsBasicHttpAuthWhenValidConfigurationGiven($config)
    {
        $connection = new Connection($config);
        $config = $connection->getConfig();
        $this->assertTrue(isset($config['key']) && isset($config['secret']));
    }

    /**
     * @dataProvider basicConfigProvider
     */
    public function testMakeCallReturnsResponseObjectWhenValidCallGiven($config)
    {
        $connection = new Connection($config);
        $response = $connection->makeCall();
        $this->assertTrue($response instanceof Response);
    }

    /**
     * @dataProvider basicConfigProvider
     */
    public function testSetConfigMergesGivenConfigWithExistingConfig($config)
    {
        $wildConfig = [
            'test' => 'this'
        ];
        $connection = new Connection($config);
        $connection->setConfig($wildConfig);
        $merged = array_merge($wildConfig, $config);
        $this->assertEquals($merged, $connection->getConfig());
    }

    public function basicConfigProvider()
    {
        return [
            [
                [
                    'key'    => 'MY_KEY',
                    'secret' => 'MY_SECRET',
                    'method' => 'get',
                    'url'    => 'https://sheetsu.com/apis/v1.0op/dc31e735c9ce',
                    'limit'  => 0,
                    'offset' => 0
                ]
            ]
        ];
    }
}
