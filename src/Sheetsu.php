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
    const BASE_URL = 'https://sheetsu.com/apis/v1.0op/';
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
        $this->initialize($config);
    }

    /**
     * Reinstantiates Connection object and changes main sheetsuId and regenerates the sheetUrl. Allows method chaining
     * @param array $config
     * @return $this
     */
    public function initialize(array $config)
    {
        if ($this->_needsNewConnectionObject($config)) {
            $this->_setConnection($config);
        }

        if (array_key_exists( 'sheetId' , $config )) {
            $this->_setSheetId($config['sheetId']);
            $this->_setSheetUrl();
        } else if (array_key_exists( 'sheetAddress' , $config )) {
             $this->sheetUrl = $config['sheetAddress'];
        }
        return $this;
    }

    private function _needsNewConnectionObject(array $config)
    {
        return (
            isset($config['key']) && isset($config['secret']) ||
            isset($config['forceNewConnection']) && $config['forceNewConnection'] === true ||
            !$this->connection instanceof Connection
        );
    }

    /**
     * Creates new Connection object with given config.
     * @param array $config
     */
    private function _setConnection(array $config)
    {
        $this->connection = new Connection($config);
    }

    private function _setSheetId($sheetId)
    {
        $this->sheetId = $sheetId;
    }

    private function _setSheetUrl()
    {
        $this->sheetUrl = self::BASE_URL . $this->sheetId;
    }

    public function _getSheetUrl()
    {
        return $this->sheetUrl;
    }


    /**
     * Appends an active sheet to the main url. Allows method chaining
     * @param $sheet
     * @return $this
     */
    public function sheet($sheet)
    {
        if (trim($sheet) !== '') {
            $this->sheetUrl .= '/sheets/' . trim($sheet);
        }
        return $this;
    }

    /**
     * Reinitializes library so as to return to use the whole spreadsheet. Allows method chaining
     * @return $this
     */
    public function whole()
    {
        $this->initialize(['sheetId' => $this->sheetId]);
        return $this;
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
     * @param bool $ignoreCase
     * @return Response
     */
    public function search(array $conditions, $limit = 0, $offset = 0, $ignoreCase = false)
    {
        $connectionConfig = [
            'method'      => 'get',
            'url'         => $this->sheetUrl . '/search',
            'conditions'  => $conditions,
            'limit'       => $limit,
            'offset'      => $offset,
            'ignore_case' => $ignoreCase
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
            return json_encode($insertData->_prepareModelAsArray());
        } elseif ($this->_isMultidimensionalArray($insertData)) {
            return json_encode(['rows' => $insertData]);
        } else {
            return json_encode(['rows' => [$insertData]]);
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
    private function _getUpdateParamsFromObject($object)
    {
        if ($this->_isValidModelInterface($object)) {
            return $object->_prepareModelAsJson();
        } else {
            return json_encode($object);
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
        return $object !== null && is_subclass_of($object, '\Sheetsu\Interfaces\CollectionInterface');
    }

    private function _isValidModelInterface($object)
    {
        return $object !== null && is_subclass_of($object, '\Sheetsu\Interfaces\ModelInterface');
    }

    private function _isMultidimensionalArray(array $array)
    {
        return isset($array[0]) && is_array($array[0]);
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
