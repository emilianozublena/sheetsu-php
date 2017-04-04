<?php
/**
 * Created by PhpStorm.
 * User: emilianozublena
 * Date: 17/3/17
 * Time: 8:55 PM
 */

namespace Sheetsu;
use Sheetsu\Interfaces\ResponseInterface;

class Response implements ResponseInterface
{
    private $curlResponse;
    private $errorHandler;

    function __construct($curlResponse) {
        $this->curlResponse = $curlResponse;
    }

    public function getHttpStatusCode(){

    }
    public function getCollection(){
        return new Collection($this->curlResponse);
    }
    public function getModel(){

    }
}