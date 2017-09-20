<?php
/**
 * Unit test for Collection class
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu\Tests;

use Sheetsu\Collection;
use Sheetsu\Model;

class CollectionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider modelProvider
     */
    public function testAddSetsAModelInArrayModelsProperty($model)
    {
        $collection = new Collection();
        $collection->add($model);
        $this->assertTrue($collection->isModelSet($model));
    }

    /**
     * @dataProvider multipleSetsProvider
     */
    public function testAddMultipleSetsModelsInArrayModelsProperty($multipleSets)
    {
        $collection = new Collection();
        $collection->addMultiple($multipleSets);
        $models = $collection->getAll();
        foreach ($models as $model) {
            $this->assertTrue($collection->isModelSet($model));
        }
    }

    /**
     * @dataProvider modelProvider
     */
    public function testDeleteUnsetsModelFromArrayModelsProperty($model)
    {
        $collection = new Collection();
        $collection->add($model);
        $collection->delete(0);
        $this->assertFalse($collection->isModelSet($model));
    }

    /**
     * @dataProvider modelProvider
     */
    public function testGetBringsAModelWithGivenKey($model)
    {
        $collection = new Collection();
        $collection->add($model);
        $getModel = $collection->get(0);

        $this->assertTrue($getModel instanceof Model);
    }

    /**
     * @dataProvider modelProvider
     */
    public function testGetFirstBringsFirstModel($model)
    {
        $collection = new Collection();
        $collection->add($model);
        $getModel = $collection->getFirst();
        $firstKeyModel = $collection->get(0);

        $this->assertEquals($getModel, $firstKeyModel);
    }

    /**
     * @dataProvider multipleSetsProvider
     */
    public function testPrepareCollectionToJsonReturnsValidJson($multipleSets)
    {
        $collection = new Collection();
        $collection->addMultiple($multipleSets);
        $this->assertTrue($collection->_prepareCollectionToJson() !== false);
    }

    /**
     * @dataProvider multipleSetsProvider
     */
    /*public function testMapAddsKeyToAllModels($multipleSets)
    {
        $collection = new Collection();
        $collection->addMultiple($multipleSets);
        $newCollection = $collection->map(function($item){
            $item->test = true;
        });
        foreach($newCollection->getModels() as $model) {
            $this->assertTrue(isset($model->test) && $model->test === true);
        }
    }
*/
    /**
     * @dataProvider multipleSetsProvider
     */
  /*  public function testTransformAddsKeyToAllModelsAndSetsItBack($multipleSets)
    {
        $collection = new Collection();
        $collection->addMultiple($multipleSets);
        $collection->transform(function($item){
            $item->test = true;
        });
        foreach($collection->getModels() as $model) {
            $this->assertTrue(isset($model->test) && $model->test === true);
        }
    }
*/
    public function multipleSetsProvider()
    {
        return [
            $this->modelProvider(),
            $this->modelProvider()
        ];
    }

    public function modelProvider()
    {
        return [
            [
                new Model([
                    'amount' => 12,
                    'costs'  => 11,
                    'date'   => '2017-10-10'
                ])
            ]
        ];
    }
}
