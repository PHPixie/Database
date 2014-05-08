<?php
namespace PHPixieTests\Database\Driver\Mongo\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Type\Update
 */
class UpdateTest extends \PHPixie\Database\Driver\Mongo\Query\ItemsTest
{
    protected $queryClass = '\PHPixie\Database\Driver\Mongo\Query\Type\Update';
    protected $type = 'update';
    
    /**
     * @covers ::data
     * @covers ::getData
     */
    public function testData()
    {
        $this->testBuilderMethod('data', array(array('test' => 1)), null, 0,$this->query);
        $this->testBuilderMethod('getData', array(), null, 1,array('test'), array('test'));
    }
}