<?php
/**
 * Example of usage for the Sheetsu PHP Library
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

ini_set('display_error', 1);
error_reporting(E_ALL);
require('vendor/autoload.php');
use Sheetsu\Sheetsu;
use Sheetsu\Collection;
use Sheetsu\Model;

$sheetsu = new Sheetsu([
    'sheetId' => 'INSERT_YOUR_SHEET_ID',
    'key' => 'INSERT_YOUR_API_KEY',
    'secret' => 'INSERT_YOUR_API_SECRET'
]);
//Creating new rows through collections
$collection = new Collection();
$collection->addMultiple([
    Model::create(['name' => 'Peter']),
    Model::create(['name' => 'Steve'])
]);
$response = $sheetsu->create($collection);
print_r($response);

//Get whole sheet
$response = $sheetsu->read();
$collection = $response->getCollection();
print_r($collection);

//Find rows matching given criteria
$response = $sheetsu->search(['name' => 'Peter']);
$collection = $response->getCollection();
print_r($collection);

//Delete rows matching given criteria
$response = $sheetsu->delete('name', 'Steve');
print_r($response);

//Updating rows matching given criteria with data of given Model
$model = Row::create(['name' => 'Stewiw']);
$response = $sheetsu->update('name', 'Peter', $model);
print_r($response);
