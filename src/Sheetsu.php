<?php
/**
 * Main class for comunicating with the Sheetsu API :)
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu;

use Sheetsu\Interfaces\CollectionInterface;
use Sheetsu\Interfaces\ModelInterface;

final class Sheetsu
{
    const BASE_URL = 'https://sheetsu.com/apis/v1.0/';
    private $connection;
    private $sheetId;
    private $sheetUrl;

    /**
     * Sheetsu constructor. Instantiates Connection object with given config
     * Sets sheetsu id for url
     * sets final url
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->connection = new Connection($config);
        $this->setSheetId($config['sheetId']);
        $this->sheetUrl = self::BASE_URL . $this->sheetId;
    }

    public function setSheetId($sheetId)
    {
        $this->sheetId = $sheetId;
    }

    /**
     * Asks full list of rows from api with given limit and offset
     * @param int $limit
     * @param int $offset
     * @return Response
     */
    public function read($limit = 0, $offset = 0)
    {
        $connectionConfig = [
            'method' => 'get',
            'url'    => $this->sheetUrl,
            'limit'  => $limit,
            'offset' => $offset
        ];

        return $this->_setConnectionConfigAndMakeCall($connectionConfig);
    }

    /**
     * Search's for given conditions in spreadsheet. It accepts an associative array to search for ie: ['name' => 'Peter']
     * @param array $conditions
     * @param int $limit
     * @param int $offset
     * @return Response
     */
    public function search(array $conditions, $limit = 0, $offset = 0)
    {
        $connectionConfig = [
            'method' => 'get',
            'url'    => $this->sheetUrl . '/search',
            'params' => $conditions,
            'limit'  => $limit,
            'offset' => $offset
        ];

        return $this->_setConnectionConfigAndMakeCall($connectionConfig);
    }

    /**
     * Creates given data in spreadsheet. Data may be a Model, Collection or Array
     * @param $insertData
     * @return Response
     */
    public function create($insertData)
    {
        $connectionConfig = [
            'method' => 'post',
            'url'    => $this->sheetUrl
        ];

        $connectionConfig['params'] = $this->_getParamsFromDataByClassInstance($insertData);

        return $this->_setConnectionConfigAndMakeCall($connectionConfig);
    }

    /**
     * Prepares data for CREATE method
     * @param $insertData
     * @return string
     */
    private function _getParamsFromDataByClassInstance($insertData)
    {
        if ($this->_isValidCollectionInterface($insertData)) {
            return '{"rows":' . $insertData->_prepareCollectionToJson() . '}';
        } elseif ($this->_isValidModelInterface($insertData)) {
            return json_encode(['rows' => $insertData->_prepareModelAsArray()]);
        } else {
            return json_encode(['rows' => $insertData]);
        }
    }

    /**
     * Updates matching $columnName + $value pair with given $updateData criteria
     * @param $columnName
     * @param $value
     * @param $updateData
     * @param bool $forcePutMethod
     * @return Response
     */
    public function update($columnName, $value, $updateData, $forcePutMethod = false)
    {
        $connectionConfig = [
            'method' => $forcePutMethod === true ? 'put' : 'patch',
            'url'    => $this->sheetUrl . '/' . $columnName . '/' . $value
        ];

        $connectionConfig['params'] = $this->_getUpdateParamsFromObject($updateData);

        return $this->_setConnectionConfigAndMakeCall($connectionConfig);
    }

    /**
     * Returns params for update function. If its a model interface, casts it as json string object
     * @param $object
     * @return mixed
     */
    private function _getUpdateParamsFromObject($object) {
        if ($this->_isValidModelInterface($object)) {
            return $object->_prepareModelAsJson();
        } else {
            return $object;
        }
    }

    /**
     * Allows to delete given $columnName + $value pair from api.
     * @param $columnName
     * @param $value
     * @return Response
     */
    public function delete($columnName, $value)
    {
        $connectionConfig = [
            'method' => 'delete',
            'url'    => $this->sheetUrl . '/' . $columnName . '/' . $value
        ];

        return $this->_setConnectionConfigAndMakeCall($connectionConfig);
    }

    private function _isValidCollectionInterface($object)
    {
        return $object !== null && $object instanceof CollectionInterface;
    }

    private function _isValidModelInterface($object)
    {
        return $object !== null && $object instanceof ModelInterface;
    }

    /**
     * Sets config in Connection class and makes the call ;)
     * @param array $connectionConfig
     * @return Response
     */
    private function _setConnectionConfigAndMakeCall(array $connectionConfig)
    {
        $this->connection->setConfig($connectionConfig);
        return $this->connection->makeCall();
    }
}