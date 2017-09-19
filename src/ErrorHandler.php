<?php
/**
 * Class for handling errors in Sheetsu PHP Library
 * It uses PHP Exception, try/catch blocks and evaluates HTTP STATUS CODE's
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu;

use ErrorException;
use Sheetsu\Interfaces\ErrorHandlerInterface;

final class ErrorHandler implements ErrorHandlerInterface
{
    private $errors;
    private $exceptions;

    function __construct($init = null)
    {
        if ($this->_isValidExceptionObject($init)) {
            $this->errors[] = $init->getMessage();
            $this->exceptions[] = $init;
        } elseif (is_array($init)) {
            foreach ($init as $exception) {
                if ($this->_isValidExceptionObject($exception)) {
                    $this->errors[] = $exception->getMessage();
                    $this->exceptions[] = $exception;
                }
            }
        }
    }

    private function _isValidExceptionObject($exception)
    {
        return $exception !== null && $exception instanceof ErrorException;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getFirstError()
    {
        return $this->errors[0];
    }

    public function getExceptions()
    {
        return $this->exceptions;
    }

    public function getFirstException()
    {
        return $this->exceptions[0];
    }

    /**
     * Checks the http_status_code and assumes that our api is sending an error in the response with the error message
     * Defines a closure and gives it to the abstracted function of try/catch block
     * @param $curl
     * @return ErrorHandler
     */
    static function checkForErrorsInCurl($curl)
    {
        $checkFunction = function () use (&$curl) {
            if ($curl->http_status_code >= 400) {
                if(!$curl->response || $curl->response == '') {
                    $message = $curl->http_status_code;
                }else {
                    $errorResponse = json_decode($curl->response);
                    $message = $errorResponse->error;
                }
                throw new ErrorException($message);
            }
        };

        return self::tryClosure($checkFunction);
    }

    static function tryClosure($closure)
    {
        try {
            return $closure();
        } catch (ErrorException $e) {
            return self::create($e);
        }
    }

    static function create(ErrorException $exception)
    {
        return new ErrorHandler($exception);
    }
}