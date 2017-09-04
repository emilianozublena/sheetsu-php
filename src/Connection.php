<?php
/**
 * The Connection class has all of the functions for validating, preparing and resolving the calls needed for the Sheetsu API
 * This class works with Curl\Curl (https://github.com/php-mod/curl/)
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
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

    /**
     * This public function prepares the query parameters, sets the final url, checks for json headers and adds parameters as json string.
     * Then, it uses Curl for making the call and returns a Response object.
     * @return Response
     */
    public function makeCall(){
        if($this->_isValidCall()) {
            $method = $this->config['method'];
            if($this->_hasLimit()) {
                $this->_prepareQueryParams();
            }
            if($this->_hasQueryParams()) {
                $this->_prepareUrlForCall();
            }
            if($this->_needsJsonHeaders()) {
                $this->_setJsonHeaders();
            }
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

    /**
     * Checks for limit and offset in params and sets it as queryParams
     */
    private function _prepareQueryParams(){
        if($this->config['limit']>0) {
            $this->config['queryParams']['limit'] = $this->config['limit'];
        }
        if($this->config['offset']>0) {
            $this->config['queryParams']['offset'] = $this->config['offset'];
        }
        unset($this->config['limit']);
        unset($this->config['offset']);
    }

    private function _hasLimit(){
        return isset($this->config['limit']);
    }

    /**
     * builds $callabeUrl with http_build_query for queryParams.
     */
    private function _prepareUrlForCall() {
        $callableUrl = $this->config['url'];
        $callableUrl .= '/?';
        $callableUrl .= http_build_query($this->config['queryParams']);
        $this->config['url'] = $callableUrl;
    }

    private function _hasQueryParams(){
        return isset($this->config['queryParams']);
    }

    /**
     * set application/json as content type header
     */
    private function _setJsonHeaders(){
        $this->curl->setHeader('Content-Type', 'application/json');
    }

    private function _needsJsonHeaders(){
        return $this->config['method']=='post' || $this->config['method']=='put' || $this->config['method']=='patch';
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
