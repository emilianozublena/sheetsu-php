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
use Sheetsu\Sheetsu;
use Sheetsu\Collection;
use Sheetsu\Model;

$sheetsu = new Sheetsu([
    'sheetId' => '3e1eaa03cb5d',
    'key' => 'ssyzhmH1UvDSwYg4ek2Q',
    'secret' => 'pbA2BqMjF6q9joiystWfXN49HsvMnnxwsyZxpHQ3'
]);
$collection = new Collection();
$collection->addMultiple([
    Model::create(['nombre' => 'hola']),
    Model::create(['nombre' => 'holas'])
]);
$response = $sheetsu->create($collection);
print_r($response);


//$response = $sheetsu->read();
//print_r($response->getCollection());

//$response = $sheetsu->search(['nombre' => 'Florencia']);
//print_r($response);

//$response = $sheetsu->delete('nombre', 'kalashnikov');
//print_r($response);

//$model = Row::create(['email' => '1sdasdasd', 'web' => 'http://google.com']);
//$response = $sheetsu->update('nombre', 'kalashnikov', $model);
//print_r($response);
