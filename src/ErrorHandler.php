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

    function __construct($exception=null) {
        if($exception!=null && $exception instanceof ErrorException) {
            $this->errors[] = $exception->getMessage();
            $this->exceptions[] = $exception;
        }
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
     * Checks the http_status_code and assumes that our api is sending a error in the response with the error message
     * Defines a closure and gives it to the abstracted funcion of try/catch block
     * @param $curl
     * @return ErrorHandler
     */
    static function checkForErrorsInCurl($curl)
    {
        $checkFunction = function() use(&$curl) {
            if($curl->http_status_code>=400) {
                $errorResponse = json_decode($curl->response);
                throw new ErrorException($errorResponse->error);
            }
        };
        return self::tryClosure($checkFunction);
    }
    static function tryClosure($closure){
        try {
            return $closure();
        }catch(ErrorException $e) {
            return self::create($e);
        }
    }
    static function create(ErrorException $exception)
    {
        return new ErrorHandler($exception);
    }
}