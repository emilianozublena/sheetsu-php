<?php
/**
 * This class receives the response from cURL and abstracts the process of creating collections or models from the api response given
 * If there are any errors, these are evaluated in the ErrorHandler class
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu;
use Sheetsu\Interfaces\ResponseInterface;
use Sheetsu\ErrorHandler;

class Response implements ResponseInterface
{
    private $curl;
    private $errorHandler;

    function __construct($curl) {
        $this->curl = $curl;
        $this->getErrorHandler();
    }

    private function getErrorHandler()
    {
        $this->errorHandler = ErrorHandler::checkForErrorsInCurl($this->curl);
    }

    public function getErrors()
    {
        return $this->errorHandler->getErrors();
    }

    public function getError(){
        return $this->errorHandler->getFirstError();
    }

    public function getExceptions()
    {
        return $this->errorHandler->getExceptions();
    }

    public function getException(){
        return $this->errorHandler->getException();
    }

    public function getCollection(){
        return new Collection($this->curl->response);
    }

    public function getModel(){
        $collection = $this->getCollection();
        return $collection->getFirst();
    }
}