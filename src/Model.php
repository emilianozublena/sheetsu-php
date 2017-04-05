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

    /**
     * updates the instantiated model with data sent.
     * data sent may be an associative array or a stdClass object
     * @param $data
     */
    public function update($data){
        $this->_storeProperties($data);
    }

    /**
     * small creator function for instantianting Models
     * @param $data
     * @return Model $instance
     */
    static function create($data) {
        return new Model($data);
    }

    /**
     * Stores the $properties given to the instantiated object.
     * the $properties may came as an associative array or a stdClass object
     * @param $properties
     */
    private function _storeProperties($properties) {
        if(is_array($properties)) {
            $this->_storePropertiesFromArray($properties);
        }else{
            $this->_storePropertiesFromStdClass($properties);
        }
    }

    /**
     * Takes $properties as an associative array and saves to instantiated object.
     * @param array $properties
     */
    private function _storePropertiesFromArray(array $properties){
        foreach($properties as $property => &$value) {
            $this->$property = &$value;
        }
    }

    /**
     * Takes $object as a stdClass object and saves its properties to the instantiated object.
     * @param $object
     */
    private function _storePropertiesFromStdClass($object) {
        foreach($object as $property => &$value) {
            $this->$property = &$value;
        }
    }

    /**
     * Returns the instantiated object as a json object.
     * @return string
     */
    public function _prepareModelAsJson()
    {
        return json_encode($this);
    }
}