<?php
/**
 * Each model represents a unique row in the google drive sheets retreived by the sheetsu api.
 * This class helps to create, store and parse rows as objects
 * This class works tightly coupled to the Collection Class
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu;

use Sheetsu\Interfaces\ModelInterface;

class Model implements ModelInterface
{
    function __construct($data) {
        $this->_storeProperties($data);
    }

    public function update($data){
        $this->_storeProperties($data);
    }

    static function create($data){
        return new Model($data);
    }

    private function _storeProperties($properties) {
        if(is_array($properties)) {
            $this->_storePropertiesFromArray($properties);
        }else{
            $this->_storePropertiesFromStdClass($properties);
        }
    }

    private function _storePropertiesFromArray(array $attributes){
        foreach($attributes as $property => &$value) {
            $this->$property = &$value;
        }
    }

    private function _storePropertiesFromStdClass($object) {
        foreach($object as $property => &$value) {
            $this->$property = &$value;
        }
    }

    public function _prepareModelAsJson()
    {
        return json_encode($this);
    }
}