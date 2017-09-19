<?php
/**
 * Interface for model objects
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu\Interfaces;


interface ModelInterface
{
    static function create($data);

    function update($data);

    function _prepareModelAsArray();

    function _prepareModelAsJson();
}