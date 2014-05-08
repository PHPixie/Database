<?php
namespace PHPixieTests\Database\Driver\Mongo\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Items
 */
abstract class ItemsTest extends ItemTest
{
     /**
     * @covers ::limit
     * @covers ::getLimit
     */
    public function testLimit()
    {
        $this->testBuilderMethod('limit', array(5), $this->query, 0); 
        $this->testBuilderMethod('getLimit', array(), 3, 3, 1); 
    }
    
    /**
     * @covers ::offset
     * @covers ::getOffset
     */
    public function testOffset()
    {
        $this->testBuilderMethod('offset', array(5), $this->query, 0); 
        $this->testBuilderMethod('getOffset', array(), 3, 1, 3);     
    }
    
    /**
     * @covers ::orderAscendingBy
     * @covers ::orderDescendingBy
     * @covers ::getOrderBy
     */
    public function testOrderBy()
    {
        $this->testBuilderMethod('orderAscendingBy', array('name'), $this->query, 0);
        $this->testBuilderMethod('orderDescendingBy', array('name'), $this->query, 1);
        $this->testBuilderMethod('getOrderBy', array(), array('test'), 2, array('test'));   
    }
}
