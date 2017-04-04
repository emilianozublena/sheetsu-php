<?php
/**
 * Created by PhpStorm.
 * User: emilianozublena
 * Date: 21/3/17
 * Time: 6:32 PM
 */
ini_set('display_error', 1);
error_reporting(E_ALL);
require('vendor/autoload.php');
$sheetsu = new \Sheetsu\Sheetsu([
    'key' => 'ssyzhmH1UvDSwYg4ek2Q',
    'secret' => 'pbA2BqMjF6q9joiystWfXN49HsvMnnxwsyZxpHQ3'
]);
$sheetsu->setSheetId('3e1eaa03cb5d');
$response = $sheetsu->read();
$sheetCollection = $response->getCollection();
var_dump($sheetCollection);