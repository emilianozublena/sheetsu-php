<?php
/**
 * Created by PhpStorm.
 * User: emilianozublena
 * Date: 17/3/17
 * Time: 8:54 PM
 */

namespace Sheetsu;
use \Sheetsu\Interfaces\ConnectionInterface;
use Curl\Curl;

class Connection implements ConnectionInterface
{
    private $curl;
    private $config;

    function __construct($config=array()){
        $this->config = $config;
        $this->curl = new Curl();
        if($this->hasAuth()) {
            $this->httpBasicAuth();
        }
    }

    private function hasAuth(){
        return isset($this->config['key']) && isset($this->config['secret']);
    }

    private function httpBasicAuth(){
        $this->curl->setBasicAuthentication($this->config['key'], $this->config['secret']);
    }

    public function makeCall(){
        if($this->isValidCall()) {
            $method = $this->config['method'];
            $data = isset($this->config['data']) ? $this->config['data'] : [];
            $callableUrl = $this->prepareUrlForCall();
            $this->curl->$method(
                $callableUrl,
                $data
            );
            return $this->createResponse($this->curl->response);
        }
    }

    private function isValidCall(){
        return isset($this->config['method']) && isset($this->config['url']);
    }

    private function prepareUrlForCall() {
        $callableUrl = $this->config['url'];
        if(isset($this->config['queryParams'])) {
            $callableUrl .= '?';
            foreach($this->config['queryParams'] as $param => $value) {
                $callableUrl .= $param.'='.$value.'&';
            }
            substr($callableUrl, 0, strlen($callableUrl)-1);
        }
        return $callableUrl;
    }

    private function createResponse($curlResponse){
        return new Response($curlResponse);
    }

    public function setConfig(array $config){
        $this->config = array_merge($config, $this->config);
    }
}