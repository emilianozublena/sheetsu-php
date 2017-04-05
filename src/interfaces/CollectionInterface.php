<?php
/**
 * Created by PhpStorm.
 * User: emilianozublena
 * Date: 17/3/17
 * Time: 9:03 PM
 */

namespace Sheetsu\Interfaces;
use Sheetsu\Model as Model;

interface CollectionInterface
{
    function __construct($curlResponse=null);
    function add($data);
    function delete($key);
    function get($key);
    function getFirst();
    function _prepareCollectionToJson();
}