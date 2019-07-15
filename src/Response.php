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
    private $http;
    private $errorHandler;

    function __construct($http)
    {
        $this->http = $http;
        $this->setErrorHandler();
    }

    /**
     * Uses static method of error handler to get an instance of it from its http client (curl object)
     */
    private function setErrorHandler()
    {
        $this->errorHandler = ErrorHandler::checkForErrorsInCurl($this->http);
    }

    public function getErrors()
    {
        return $this->errorHandler->getErrors();
    }

    public function getError()
    {
        return $this->errorHandler->getFirstError();
    }

    public function getExceptions()
    {
        return $this->errorHandler->getExceptions();
    }

    public function getException()
    {
        return $this->errorHandler->getFirstException();
    }

    public function getHttpStatus()
    {
        return $this->http->http_status_code;
    }
    
    public function getResponse()
    {
        return $this->http->response;
    }
    
    public function getCollection()
    {
        return new Collection($this->http->response);
    }

    public function getModel()
    {
        $collection = $this->getCollection();
        return $collection->getFirst();
    }
}
