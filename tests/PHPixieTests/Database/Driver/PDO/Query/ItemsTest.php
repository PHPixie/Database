<?php

namespace PHPixieTests\Database\Driver\PDO\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query\Items
 */
class ItemsTest extends \PHPixieTests\Database\Driver\PDO\QueryTest
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
    
    /**
     * @covers ::join
     * @covers ::clearJoins
     * @covers ::getJoins
     */
    public function testJoin()
    {
        $this->setClearGetTest('join', array(
            array(array('test', 't', 'left')),
            array(array('test'), array('test', null, 'inner')),
        ), 'array', 'joins');
    }

    /**
     * @covers ::<protected>
     * @covers ::getWhereBuilder
     * @covers ::getWhereConditions
     * @covers ::where
     * @covers ::andWhere
     * @covers ::orWhere
     * @covers ::xorWhere
     * @covers ::whereNot
     * @covers ::andWhereNot
     * @covers ::orWhereNot
     * @covers ::xorWhereNot
     * @covers ::startWhereGroup
     * @covers ::startAndWhereGroup
     * @covers ::startOrWhereGroup
     * @covers ::startXorWhereGroup
     * @covers ::startWhereNotGroup
     * @covers ::startAndWhereNotGroup
     * @covers ::startOrWhereNotGroup
     * @covers ::startXorWhereNotGroup
     * @covers ::endWhereGroup
     */
    public function testWhereMethods()
    {
        $this->conditionMethodsTest('where');
    }
    
    /**
     * @covers ::<protected>
     * @covers ::_and
     * @covers ::_or
     * @covers ::_xor
     * @covers ::_not
     * @covers ::_andNot
     * @covers ::_orNot
     * @covers ::_xorNot
     * @covers ::startGroup
     * @covers ::startAndGroup
     * @covers ::startOrGroup
     * @covers ::startXorGroup
     * @covers ::startNotGroup
     * @covers ::startAndNotGroup
     * @covers ::startOrNotGroup
     * @covers ::startXorNotGroup
     * @covers ::endGroup
     */
    public function testShorthandMethods()
    {
        $this->conditionMethodsTest(null, false);
    }

    /**
     * @covers ::<protected>
     * @covers ::on
     * @covers ::andOn
     * @covers ::orOn
     * @covers ::xorOn
     * @covers ::onNot
     * @covers ::andOnNot
     * @covers ::orOnNot
     * @covers ::xorOnNot
     * @covers ::startOnGroup
     * @covers ::startAndOnGroup
     * @covers ::startOrOnGroup
     * @covers ::startXorOnGroup
     * @covers ::startOnNotGroup
     * @covers ::startAndOnNotGroup
     * @covers ::startOrOnNotGroup
     * @covers ::startXorOnNotGroup
     * @covers ::endOnGroup
     */
    public function testOnMethods()
    {
        $this->conditionMethodsTest('on', false, 'addOnCondition', 'startOnConditionGroup', 'endOnConditionGroup', false);
    }
}