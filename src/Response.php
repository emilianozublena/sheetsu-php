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
    private $curl;

    function __construct($curl) {
        $this->curl = $curl;
    }

    public function getHttpStatusCode(){
        return $this->curl->http_status_code;
    }

    public function getCollection(){
        return new Collection($this->curl->response);
    }

    public function getModel(){
        $collection = $this->getCollection();
        return $collection->getFirst();
    }
}