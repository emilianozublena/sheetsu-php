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
    function __construct($data = null)
    {
        if ($data !== null) {
            $this->_storeProperties($data);
        }
    }

    /**
     * updates the instantiated model with data sent.
     * data sent may be an associative array or a stdClass object
     * @param $data
     */
    public function update($data)
    {
        $this->_storeProperties($data);
    }

    /**
     * small creator function for instantianting Models
     * @param $data
     * @return Model $instance
     */
    static function create($data)
    {
        return new Model($data);
    }

    
    /**
     * Get value of property
     * @param $item
     * @return Mixed $item
     */
    public function get($item) {
        if(!isset($this -> $item)) {
            throw new Exception("Undefined Index `".$item."`");
        }else {
            return $this -> $item;
        }
    }
    
    /**
    * Get value of property or null
    * @param $item
    * @return Mixed $item
    */
    public function getOrNull($item) {
        if(!isset($this -> $item)) {
            return null;
        }else {
            return $this -> $item;
        }
    }
    
    /**
     * Stores the $properties given to the instantiated object.
     * the $properties may came as an associative array or a stdClass object
     * @param $properties
     */
    private function _storeProperties($properties)
    {
        if (is_array($properties)) {
            $this->_storePropertiesFromArray($properties);
        } elseif($properties instanceof \stdClass) {
            $this->_storePropertiesFromStdClass($properties);
        }
    }

    /**
     * Takes $properties as an associative array and saves to instantiated object.
     * @param array $properties
     */
    private function _storePropertiesFromArray(array $properties)
    {
        foreach ($properties as $property => &$value) {
            $this->$property = &$value;
        }
    }

    /**
     * Takes $object as a stdClass object and saves its properties to the instantiated object.
     * @param $object
     */
    private function _storePropertiesFromStdClass(\stdClass $object)
    {
        foreach ($object as $property => &$value) {
            $this->$property = &$value;
        }
    }

    /**
     * Returns the instantiated object as array.
     * @return array
     */
    public function _prepareModelAsArray()
    {
        $arObject = [];
        $object = $this;
        foreach ($object as $property => $value) {
            $arObject[$property] = $value;
        }

        return $arObject;
    }

    /**
     * Returns the instantiated object as a valid json string.
     * @return string
     */
    public function _prepareModelAsJson()
    {
        return json_encode($this);
    }
}
