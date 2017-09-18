<?php
/**
 * Unit test for Sheetsu main class
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu\Tests;


use Sheetsu\Collection;
use Sheetsu\Response;
use Sheetsu\Sheetsu;
use Sheetsu\Model;

class SheetsuTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validGetConfigProvider
     */
    public function testConstructSetsConnectionSheetIdAndSheetUrlFromConfig($config)
    {
        $sheetsu = new Sheetsu([
            'sheetId' => $config['sheetId']
        ]);
        $response = $sheetsu->read();
        $collection = $response->getCollection();
        $this->assertTrue($collection instanceof Collection);
    }

    /**
     * @dataProvider validGetConfigProvider
     */
    public function testReadGetsAResponseObjectAndCollectionObject($config)
    {
        $sheetsu = new Sheetsu([
            'sheetId' => $config['sheetId']
        ]);
        $response = $sheetsu->read($config['limit'], $config['offset']);
        $collection = $response->getCollection();
        $this->assertTrue($response instanceof Response && $collection instanceof Collection);
    }

    /**
     * @dataProvider validGetConfigProvider
     */
    public function testSearchGetsAResponseObjectAndCollectionObject($config)
    {
        $sheetsu = new Sheetsu([
            'sheetId' => $config['sheetId']
        ]);
        $response = $sheetsu->search($config['conditions'], $config['limit'], $config['offset']);
        $collection = $response->getCollection();
        $this->assertTrue($response instanceof Response && $collection instanceof Collection);
    }

    public function invalidConfigProvider()
    {
        return [
            [
                [
                    'method'  => 'get',
                    'sheetId' => 'asdad12',
                    'limit'   => 0,
                    'offset'  => 0
                ]
            ]
        ];
    }

    public function validGetConfigProvider()
    {
        return [
            [
                [
                    'method'     => 'get',
                    'sheetId'    => 'dc31e735c9ce',
                    'limit'      => 0,
                    'offset'     => 0,
                    'conditions' => ['name' => 'Peter']
                ],
                [
                    'method'     => 'get',
                    'sheetId'    => 'dc31e735c9ce',
                    'limit'      => 1,
                    'offset'     => 0,
                    'conditions' => ['name' => 'Peter']
                ],
                [
                    'method'     => 'get',
                    'sheetId'    => 'dc31e735c9ce',
                    'limit'      => 1,
                    'offset'     => 1,
                    'conditions' => ['name' => 'Peter']
                ]
            ]
        ];
    }
}