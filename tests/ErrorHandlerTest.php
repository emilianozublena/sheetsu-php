<?php
/**
 * Unit test for Error Handler class
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu\Tests;

use Sheetsu\ErrorHandler;

class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider exceptionProvider
     * @param $exception
     */
    public function testConstructSetsExceptionAndExceptionMessageInArrayAttributes($exception)
    {
        $errorHandler = new ErrorHandler($exception);
        $this->assertTrue($errorHandler->getFirstException() instanceof \ErrorException);
    }

    /**
     * @dataProvider arExceptionsProvider
     * @param $exceptions
     */
    public function testGetErrorsReturnsArrayOfErrors($exceptions)
    {
        $errorHandler = new ErrorHandler($exceptions);
        $errors = $errorHandler->getErrors();
        $this->assertTrue(is_array($errors));
    }

    /**
     * @dataProvider arExceptionsProvider
     * @param $exceptions
     */
    public function testGetFirstErrorReturnsFirstError($exceptions)
    {
        $errorHandler = new ErrorHandler($exceptions);
        $firstError = $errorHandler->getFirstError();
        $this->assertEquals('This is a message', $firstError);
    }

    /**
     * @dataProvider arExceptionsProvider
     * @param $exceptions
     */
    public function testGetExceptionsReturnsArrayOfExceptions($exceptions)
    {
        $errorHandler = new ErrorHandler($exceptions);
        $exceptions = $errorHandler->getExceptions();
        $this->assertTrue(is_array(($exceptions)));
    }

    /**
     * @dataProvider arExceptionsProvider
     * @param $exceptions
     */
    public function testGetFirstExceptionReturnsFirstException($exceptions)
    {
        $errorHandler = new ErrorHandler($exceptions);
        $exception = $errorHandler->getFirstException();
        $this->assertTrue($exception instanceof \ErrorException);
    }


    /**
     * @dataProvider curlProvider
     * @param $curl
     */
    public function testCheckForErrorsInCurlChecksHttpStatusCodeOkInGivenCurlObject($curl)
    {
        $result = ErrorHandler::checkForErrorsInCurl($curl);
        $exception = $result->getFirstException();
        $this->assertTrue($exception instanceof \ErrorException);
    }


    public function exceptionProvider()
    {
        $exception = new \ErrorException('This is a message');
        return [
            [
                $exception
            ]
        ];
    }

    public function arExceptionsProvider()
    {
        $exception = new \ErrorException('This is a message');
        return [
            [
                $exception,
                $exception,
                $exception
            ]
        ];
    }

    public function curlProvider()
    {
        $one = new \stdClass();
        $one->http_status_code = 401;
        $one->response = '{
            "glossary": {
                "title": "example glossary",
                "GlossDiv": {
                    "title": "S",
                    "GlossList": {
                        "GlossEntry": {
                            "ID": "SGML",
                            "SortAs": "SGML",
                            "GlossTerm": "Standard Generalized Markup Language",
                            "Acronym": "SGML",
                            "Abbrev": "ISO 8879:1986",
                            "GlossDef": {
                                "para": "A meta-markup language, used to create markup languages such as DocBook.",
                                "GlossSeeAlso": ["GML", "XML"]
                            },
                            "GlossSee": "markup"
                        }
                    }
                }
            },
            "error": "This is a message"
        }';
        $two = new \stdClass();
        $two->http_status_code = 500;
        $two->response = '{
            "glossary": {
                "title": "example glossary",
                "GlossDiv": {
                    "title": "S",
                    "GlossList": {
                        "GlossEntry": {
                            "ID": "SGML",
                            "SortAs": "SGML",
                            "GlossTerm": "Standard Generalized Markup Language",
                            "Acronym": "SGML",
                            "Abbrev": "ISO 8879:1986",
                            "GlossDef": {
                                "para": "A meta-markup language, used to create markup languages such as DocBook.",
                                "GlossSeeAlso": ["GML", "XML"]
                            },
                            "GlossSee": "markup"
                        }
                    }
                }
            },
            "error": "This is a message"
        }';
        return [
            [$one],
            [$two]
        ];
    }
}
