<?php
/**
 * Unit test for Response class
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu\Tests;


use Sheetsu\Collection;
use Sheetsu\Connection;
use Sheetsu\Model;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider basicInvalidConnectionConfigProvider
     */
    public function testConstructSetsHttpClientAndErrorHandler($config)
    {
        $connection = new Connection($config);
        $response = $connection->makeCall();
        $exception = $response->getException();
        $this->assertTrue($exception instanceof \ErrorException);
    }

    /**
     * @dataProvider basicInvalidConnectionConfigProvider
     */
    public function testGetErrorsGetsAnArrayOfErrors($config)
    {
        $connection = new Connection($config);
        $response = $connection->makeCall();
        $errors = $response->getErrors();
        $this->assertTrue(is_array($errors));
    }

    /**
     * @dataProvider basicInvalidConnectionConfigProvider
     */
    public function testGetErrorGetsFirstError($config)
    {
        $connection = new Connection($config);
        $response = $connection->makeCall();
        $errors = $response->getErrors();
        $error = $response->getError();
        $this->assertEquals($errors[0], $error);
    }

    /**
     * @dataProvider basicInvalidConnectionConfigProvider
     */
    public function testGetExceptionsGetsAnArrayOfExceptions($config)
    {
        $connection = new Connection($config);
        $response = $connection->makeCall();
        $exceptions = $response->getExceptions();
        foreach ($exceptions as $exception) {
            $this->assertTrue($exception instanceof \ErrorException);
        }
    }

    /**
     * @dataProvider basicInvalidConnectionConfigProvider
     */
    public function testGetExceptionGetsFirstException($config)
    {
        $connection = new Connection($config);
        $response = $connection->makeCall();
        $exceptions = $response->getExceptions();
        $exception = $response->getException();
        $this->assertEquals($exceptions[0], $exception);
    }

    /**
     * @dataProvider basicValidConnectionConfigProvider
     */
    public function testGetCollectionGestACollectionObjectFromHttpResponse($config)
    {
        $connection = new Connection($config);
        $response = $connection->makeCall();
        $collection = $response->getCollection();
        $this->assertTrue($collection instanceof Collection);
    }

    /**
     * @dataProvider basicValidConnectionConfigProvider
     */
    public function testGetModelGestAModelObjectFromHttpResponse($config)
    {
        $connection = new Connection($config);
        $response = $connection->makeCall();
        $model = $response->getModel();
        $this->assertTrue($model instanceof Model);
    }

    public function basicInvalidConnectionConfigProvider()
    {
        return [
            [
                [
                    'key'    => 'MY_KEY',
                    'secret' => 'MY_SECRET',
                    'method' => 'get',
                    'url'    => 'https://sheetsu.com/apis/v1.0op/asda123',
                    'limit'  => 0,
                    'offset' => 0
                ]
            ]
        ];
    }

    public function basicValidConnectionConfigProvider()
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
