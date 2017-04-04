<?php
/**
 * Created by PhpStorm.
 * User: emilianozublena
 * Date: 17/3/17
 * Time: 8:54 PM
 */

namespace Sheetsu;

use Sheetsu\Interfaces\CollectionInterface as CollectionInterface;

class Collection implements CollectionInterface
{
    private $items;

    function __construct($curlResponse){
        $this->prepareCollectionFromJson($curlResponse);
    }

    public function add(){

    }
    public function update(){

    }
    public function delete(){

    }
    public function get(){

    }
    public function where(){

    }
    function findWhere(){

    }

    function prepareCollectionFromJson($json)
    {
        $arResponse = json_decode($json);
        foreach($arResponse as $key => $row){

            $model = new Model();

            foreach($row as $property => &$value)
            {
                $model->$property = &$value;
                unset($row->$property);
            }

            $this->items[] = $model;
        }
    }
}