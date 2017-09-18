<?php
/**
 * Unit test for Model class
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu\Tests;


use Sheetsu\Model;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider sampleModelInputDataProvider
     */
    public function testStorePropertiesSetsGivenDataAsAttributesInConstruct($testData)
    {
        $model = new Model($testData);
        $this->assertEquals(22, $model->amount);
        $this->assertEquals(12, $model->costs);
        $this->assertEquals('2018-10-10', '2018-10-10');
    }

    /**
     * @dataProvider sampleModelInputDataProvider
     */
    public function testStorePropertiesSetsGivenDataAsAttributesInCreate($testData)
    {
        $model = Model::create($testData);
        $this->assertEquals(22, $model->amount);
        $this->assertEquals(12, $model->costs);
        $this->assertEquals('2018-10-10', '2018-10-10');
    }

    /**
     * @dataProvider sampleModelInputDataProvider
     */
    public function testStorePropertiesSetsGivenDataAsAttributesInUpdate($testData)
    {
        $model = new Model();
        $model->update($testData);
        $this->assertEquals(22, $model->amount);
        $this->assertEquals(12, $model->costs);
        $this->assertEquals('2018-10-10', '2018-10-10');
    }

    public function sampleModelInputDataProvider()
    {
        $testClass = new \stdClass();
        $testClass->amount = 22;
        $testClass->costs = 12;
        $testClass->date = '2018-10-10';
        return [
            [['amount' => 22, 'costs' => 12, 'date' => '2018-10-10']],
            [$testClass]
        ];
    }
}