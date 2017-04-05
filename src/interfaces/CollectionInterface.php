<?php
/**
 * Interface for collection objects
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu\Interfaces;

interface CollectionInterface
{
    function __construct($curlResponse=null);
    function add($data);
    function delete($key);
    function get($key);
    function getFirst();
    function _prepareCollectionToJson();
}