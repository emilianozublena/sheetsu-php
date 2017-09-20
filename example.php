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
    'sheetId' => 'INSERT_YOUR_SHEET_ID'
]);

//Creating new rows through collections
$collection = new Collection();
$collection->addMultiple([
    Model::create(['name' => 'Peter']),
    Model::create(['name' => 'Steve'])
]);
$response = $sheetsu->create($collection);

//through array of models
$response = $sheetsu->create([
    Model::create(['name' => 'Peter']),
    Model::create(['name' => 'Steve'])
]);
//through array of arrays
$response = $sheetsu->create([
    ['name' => 'Peter'],
    ['name' => 'Steve']
]);

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
$model = Model::create(['name' => 'Stewiw']);
$response = $sheetsu->update('name', 'Peter', $model);
print_r($response);

//Change sheet id and whole spreadsheet with method chaining.
$response = $sheetsu->initialize('myNewSheetId')->read();
$collection = $response->getCollection();
print_r($collection);

//Make use of a specific sheet in next and all further calls
$response = $sheetsu->sheet('sheetName')->read();
$collection = $response->getCollection();
print_r($collection);

//Stop using specific sheet
$sheetsu->sheet('sheet2');
$response = $sheetsu->sheet('sheet2')->read();
$collection = $response->getCollection();
print_r($collection);