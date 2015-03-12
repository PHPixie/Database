<?php
namespace PHPixie\Tests\Database\Driver\Mongo\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Items
 */
abstract class ItemsTest extends ItemTest
{
    /**
     * @covers ::limit
     * @covers ::clearLimit
     * @covers ::getLimit
     */
    public function testLimit()
    {
         $this->setClearGetTest('limit', array(
            array(array(5)),
        ));
    }
    
    /**
     * @covers ::offset
     * @covers ::clearOffset
     * @covers ::getOffset
     */
    public function testOffset()
    {
        $this->setClearGetTest('offset', array(
            array(array(5)),
        ));
    }
    
    /**
     * @covers ::orderAscendingBy
     * @covers ::orderDescendingBy
     * @covers ::clearOrderBy
     * @covers ::getOrderBy
     */
    public function testOrderBy()
    {
        $this->builderMethodTest('orderAscendingBy', array('name'), $this->query, null, null, 'addOrderAscendingBy');
        $this->builderMethodTest('orderDescendingBy', array('name'), $this->query, null, null, 'addOrderDescendingBy');
        $this->clearGetTest('orderBy', 'array');
    }
}
