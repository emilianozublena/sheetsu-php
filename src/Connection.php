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
        if($this->_hasAuth()) {
            $this->_httpBasicAuth();
        }
    }

    private function _hasAuth(){
        return isset($this->config['key']) && isset($this->config['secret']);
    }

    private function _httpBasicAuth(){
        $this->curl->setBasicAuthentication($this->config['key'], $this->config['secret']);
    }

    public function makeCall(){
        if($this->_isValidCall()) {
            $method = $this->config['method'];
            $this->_prepareQueryParams();
            $this->_prepareUrlForCall();
            $this->_setJsonHeaders();
            $this->_prepareParametersAsJson();
            $this->curl->$method(
                $this->config['url'].'/',
                $this->config['params'],
                true
            );
            return $this->_createResponse($this->curl);
        }
    }

    private function _isValidCall(){
        return isset($this->config['method']) && isset($this->config['url']);
    }

    private function _prepareQueryParams(){
        if(isset($this->config['limit'])) {
            if($this->config['limit']>0) {
                $this->config['queryParams']['limit'] = $this->config['limit'];
            }
            if($this->config['limit']>0) {
                $this->config['queryParams']['offset'] = $this->config['offset'];
            }
            unset($this->config['limit']);
            unset($this->config['offset']);
        }
    }

    private function _prepareUrlForCall() {
        $callableUrl = $this->config['url'];
        if(isset($this->config['queryParams'])) {
            $callableUrl .= '/?';
            $callableUrl .= http_build_query($this->config['queryParams']);
        }
        $this->config['url'] = $callableUrl;
    }

    private function _setJsonHeaders(){
        if($this->config['method']=='post' || $this->config['method']=='put' || $this->config['method']=='patch') {
            $this->curl->setHeader('Content-Type', 'application/json');
        }
    }

    private function _prepareParametersAsJson(){
        if(isset($this->config['params']) && is_array($this->config['params'])) {
            $this->config['params'] = json_encode($this->config['params']);
        }elseif(!isset($this->config['params'])){
            $this->config['params'] = json_encode([]);
        }
    }

    private function _createResponse($curlResponse){
        return new Response($curlResponse);
    }

    public function setConfig(array $config){
        $this->config = array_merge($config, $this->config);
    }
}