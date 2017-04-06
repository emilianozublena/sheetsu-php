<?php
/**
 * This class helps to manage and group models given by Sheetsu API as collections of models.
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu;

use Sheetsu\Interfaces\CollectionInterface;
use Sheetsu\Model;

class Collection implements CollectionInterface
{
    private $models;

    function __construct($curlResponse=null){
        $this->_prepareCollectionFromJson($curlResponse);
    }

    /**
     * Adds given implementation of Model to the collection.
     * @param Model $model
     */
    public function add(Model $model){
        if(!$this->_isModelSet($model)){
            $this->models[] = $model;
        }
    }

    /**
     * Adds an associative array of Model implementations to the collection.
     * @param array $models
     */
    public function addMultiple(array $models) {
        foreach($models as $key => $model) {
            $this->add($model);
        }
    }

    /**
     * Deletes model in collection by given key
     * @param $key
     * @return bool
     */
    public function delete($key){
        //reemplazar por exception?
        if($this->_isKeySet($key)) {
            unset($this->models[$key]);
            return true;
        }
        return false;
    }

    /**
     * Takes $key, checks if its set and returns given Model. If there's none, returns null.
     * @param $key
     * @return Model || null;
     */
    public function get($key){
        return $this->_isKeySet($key) ? $this->models[$key] : null;
    }

    public function getFirst()
    {
        return $this->_hasModels() ? $this->models[0] : null;
    }

    private function _isModelSet(Model $model)
    {
        $key = $this->_getKeyFromModel($model);
        return isset($this->models[$key]) && $model instanceof Model;
    }

    private function _getKeyFromModel(Model $model){
        foreach($this->models as $key => $storedModel) {
            if($model===$storedModel) {
                return $key;
            }
        }
    }

    private function _isKeySet($key) {
        return isset($this->models[$key]);
    }

    private function _hasModels()
    {
        return count($this->models)>0 && isset($this->models[0]);
    }

    /**
     * Takes json string, decodes it into an associative array of stdClass objects
     * And then create models and calls for add() to add it to the collection.
     * @param $json
     */
    private function _prepareCollectionFromJson($json)
    {
        $arResponse = json_decode($json);
        foreach($arResponse as $key => $model){
            $model = Model::create($model);
            $this->add($model);
        }
    }

    public function _prepareCollectionToJson() {
        return json_encode($this->models);
    }

    public function getModels(){
        return $this->models;
    }

    /**
     * This function expects a closure or anon function to exec for every model in the collection.
     * @param $closure
     * @return array
     */
    public function _doClosureForWholeCollection($closure){
        return array_map($closure, $this->models);
    }
}