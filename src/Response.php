<?php
/**
 * This class receives the response from cURL and abstracts the process of creating collections or models from the api response given
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
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

    public function getRow(){
        $collection = $this->getCollection();
        return $collection->getFirst();
    }
}