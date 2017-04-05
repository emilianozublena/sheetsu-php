<?php
/**
 * Created by PhpStorm.
 * User: emilianozublena
 * Date: 17/3/17
 * Time: 9:03 PM
 */

namespace Sheetsu\Interfaces;


interface ModelInterface
{
    static function create($data);
    function update($data);
    function _prepareModelAsJson();
}