<?php
/**
 * This class helps to manage and group rows given by Sheetsu API as collections of models.
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu;

use Sheetsu\Interfaces\CollectionInterface as CollectionInterface;

class Collection implements CollectionInterface
{
    private $models;

    function __construct($curlResponse=null){
        $this->_prepareCollectionFromJson($curlResponse);
    }

    public function add($data){
        if($data instanceof Model && !$this->_isModelSet($data)){
            $this->models[] = $data;
        }elseif(is_array($data)) {
            foreach($data as $key => $model) {
                $this->add($model);
            }
        }
    }

    public function delete($key){
        if($this->_isKeySet($key)) {
            unset($this->models[$key]);
        }
    }

    public function get($key){
        if($this->_isKeySet($key)){
            return $this->models[$key];
        }
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

    private function _prepareCollectionFromJson($json)
    {
        $arResponse = json_decode($json);
        foreach($arResponse as $key => $row){
            $model = Model::create($row);
            $this->models[] = $model;
        }
    }

    public function _prepareCollectionToJson() {
        return json_encode($this->models);
    }

    public function getModels(){
        return $this->models;
    }
}