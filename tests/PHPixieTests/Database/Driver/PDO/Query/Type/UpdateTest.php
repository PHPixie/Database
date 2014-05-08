<?php
namespace PHPixieTests\Database\Driver\PDO\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query\Type\Update;
 */
class UpdateTest extends \PHPixieTests\Database\Driver\PDO\Query\Implementation\ItemTest
{
    protected $queryClass = '\PHPixie\Database\Driver\PDO\Query\Type\Update';

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