<?php
/**
 * Created by PhpStorm.
 * User: emilianozublena
 * Date: 17/3/17
 * Time: 6:51 PM
 */

namespace Sheetsu;

final class Sheetsu
{
    private $connection;
    private $sheetId;
    const BASE_URL = 'https://sheetsu.com/apis/v1.0/';

    public function __construct($config=array()){
        $this->connection = new Connection($config);
    }

    public function setSheetId($sheetId){
        $this->sheetId = $sheetId;
    }

    public function read($limit=0, $offset=0){
        $connectionConfig = [
            'method'    => 'get',
            'url'       => self::BASE_URL.$this->sheetId
        ];

        if($limit>0) {
            $connectionConfig['queryParams']['limit'] = $limit;
        }
        if($offset>0) {
            $connectionConfig['queryParams']['offset'] = $offset;
        }

        $this->connection->setConfig($connectionConfig);

        return $this->connection->makeCall();
    }

    public function search(array $conditions, $limit=0, $offset=0){

    }

    public function create(array $rows) {

    }

    public function update($column, $value, array $updateBatch) {

    }

    public function delete($column, $value){

    }
}