<?php
/**
 * Created by PhpStorm.
 * User: emilianozublena
 * Date: 17/3/17
 * Time: 9:03 PM
 */

namespace Sheetsu\Interfaces;


interface CollectionInterface
{
    function __construct($curlResponse);
    function add();
    function update();
    function delete();
    function get();
    function where();
    function findWhere();
    function prepareCollectionFromJson($json);
}