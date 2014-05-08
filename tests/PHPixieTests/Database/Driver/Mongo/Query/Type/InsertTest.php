<?php
namespace PHPixieTests\Database\Driver\Mongo\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Type\Insert
 */
class InsertTest extends \PHPixie\Database\Driver\Mongo\Query\ItemsTest
{
    protected $queryClass = '\PHPixie\Database\Driver\Mongo\Query\Type\Insert';
    protected $type = 'insert';
    
    /**
     * @covers ::data
     * @covers ::batchData
     * @covers ::getData
     */
    public function testData()
    {
        $this->testBuilderMethod('data', array(array('test' => 1)), null, 0,$this->query);
        $this->testBuilderMethod('getData', array(), null, 1,array('test'), array('test'));
        $this->testBuilderMethod('batchData', array(1, 2), null, 2,$this->query);
    }
}