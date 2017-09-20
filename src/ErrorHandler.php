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
        $this->initialize($init);
    }

    public function initialize($init)
    {
        if (is_array($init)) {
            array_map(
                [$this, '_setErrorExceptionInHandlerIfValid'], $init
            );
        } else {
            $this->_setErrorExceptionInHandlerIfValid($init);
        }
    }

    /**
     * Sets the parameter if its a valid ErrorException object
     * @param $exception
     */
    private function _setErrorExceptionInHandlerIfValid($exception)
    {
        if($this->_isValidExceptionObject($exception)) {
            $this->_setErrorExceptionInHandler($exception);
        }
    }

    /**
     * Sets an ErrorException object in the ErrorHandler
     * @param array $arExceptions
     */
    private function _setErrorExceptionInHandler(ErrorException $exception)
    {
        $this->errors[] = $exception->getMessage();
        $this->exceptions[] = $exception;
    }

    /**
     * Checks if given variable is a valid ErrorException object
     * @param $exception
     * @return bool
     */
    private function _isValidExceptionObject($exception)
    {
        return $exception !== null && $exception instanceof ErrorException;
    }

    /**
     * Returns private attribute errors
     * @return array | null
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Expects private attribute errors to be an array and returns first item
     * @return string
     */
    public function getFirstError()
    {
        return $this->errors[0];
    }

    /**
     * Returns private attribute exceptions
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * Expects private attribute exceptions to be an array and returns first item
     * @return ErrorException
     */
    public function getFirstException()
    {
        return $this->exceptions[0];
    }

    /**
     * Checks the http_status_code and assumes that our api is sending an error in the response with the error message
     * Defines a closure and gives it to the abstracted function of try/catch block
     * @param $client
     * @return ErrorHandler
     */
    static function checkForErrorsInCurl($client)
    {
        return self::tryStatic(['self', 'throwExceptionByHttpStatusCode'], [$client]);
    }

    /**
     * Takes an array that has an object and its static method and executes it with given arguments inside a try/catch block.
     * @param $data
     * @param $arguments
     * @return mixed|ErrorHandler
     */
    static function tryStatic($data, $arguments)
    {
        try {
            return call_user_func_array($data[0] . '::' . $data[1], $arguments);
        } catch (ErrorException $e) {
            return self::create($e);
        }
    }

    /**
     * Expects some kind of client that has http_status_code response from last call, and a response json string
     * Checks if there are any errors (code >= 400) and throws corresponding exceptions
     * (now its always Curl, but it should be any in the future)
     * @param $client
     * @throws ErrorException
     */
    static function throwExceptionByHttpStatusCode($client)
    {
        if ($client->http_status_code >= 400) {
            $errorResponse = json_decode($client->response);
            if ($errorResponse == null) {
                $message = $client->http_status_code;
            } else {
                $errorResponse = json_decode($client->response);
                $message = $errorResponse->error;
            }
            throw new ErrorException($message);
        }
    }

    /**
     * Static factory, creates error handler object with given exception
     * @param $exception
     * @return ErrorHandler
     */
    static function create($exception)
    {
        return new ErrorHandler($exception);
    }
}