<?php

namespace PHPixieTests\Database\Driver\PDO\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query\Items
 */
abstract class ItemsTest extends \PHPixieTests\Database\Driver\PDO\QueryTest
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
     * @covers ::getWhereContainer
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
     * @covers ::addWhereOperatorCondition
     * @covers ::startWhereConditionGroup
     * @covers ::addWherePlaceholder
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
     * @covers ::andNot
     * @covers ::orNot
     * @covers ::xorNot
     * @covers ::startGroup
     * @covers ::startAndGroup
     * @covers ::startOrGroup
     * @covers ::startXorGroup
     * @covers ::startNotGroup
     * @covers ::startAndNotGroup
     * @covers ::startOrNotGroup
     * @covers ::startXorNotGroup
     * @covers ::endGroup
     * @covers ::addOperatorCondition
     * @covers ::startConditionGroup
     * @covers ::addPlaceholder
     */
    public function testShorthandMethods()
    {
        $this->conditionMethodsTest(null, false);
    }
    
    /**
     * @covers ::__call
     */
    public function testAliasedConditionMethods()
    {
        $this->conditionAliasTest();
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
     * @covers ::addOnOperatorCondition
     * @covers ::startOnConditionGroup
     * @covers ::addOnPlaceholder
     */
    public function testOnMethods()
    {
        $methods = array(
            'addCondition' => 'addOnCondition',
            'startConditionGroup' => 'startOnConditionGroup',
            'endConditionGroup' => 'endOnConditionGroup',
            'addOperatorCondition' => 'addOnOperatorCondition',
            'addPlaceholder' => 'addOnPlaceholder',
        );
        $this->conditionMethodsTest('on', false, $methods, false);
    }
}