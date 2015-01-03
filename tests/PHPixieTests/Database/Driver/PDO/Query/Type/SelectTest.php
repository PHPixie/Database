<?php
namespace PHPixieTests\Database\Driver\PDO\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query\Type\Select
 */
class SelectTest extends \PHPixieTests\Database\Driver\PDO\Query\ItemsTest
{
    protected $queryClass = '\PHPixie\Database\Driver\PDO\Query\Type\Select';
    protected $type = 'select';
    
    /**
     * @covers ::fields
     * @covers ::clearFields
     * @covers ::getFields
     */
    public function testFields()
    {
        $this->setClearGetTest('fields', array(
            array(array('pixie'), array(array('pixie'))),
        ), 'array');
    }
    
    /**
     * @covers ::groupBy
     * @covers ::clearGroupBy
     * @covers ::getGroupBy
     */
    public function testGroupBy()
    {
        $this->setClearGetTest('groupBy', array(
            array(array('pixie'), array(array('pixie'))),
        ), 'array');
    }
    
    /**
     * @covers ::union
     * @covers ::clearUnions
     * @covers ::getUnions
     */
    public function testUnion()
    {
        $this->setClearGetTest('union', array(
            array(array('pixie', true)),
            array(array('pixie'), array('pixie', false)),
        ), 'array', 'unions');
    }

    /**
     * @covers ::<protected>
     * @covers ::addHavingCondition
     * @covers ::buildHavingCondition
     * @covers ::getHavingContainer
     * @covers ::getHavingConditions
     * @covers ::having
     * @covers ::andHaving
     * @covers ::orHaving
     * @covers ::xorHaving
     * @covers ::havingNot
     * @covers ::andHavingNot 
     * @covers ::orHavingNot
     * @covers ::xorHavingNot
     * @covers ::startHavingGroup
     * @covers ::startAndHavingGroup
     * @covers ::startOrHavingGroup
     * @covers ::startXorHavingGroup
     * @covers ::startHavingNotGroup
     * @covers ::startAndHavingNotGroup
     * @covers ::startOrHavingNotGroup
     * @covers ::startXorHavingNotGroup
     * @covers ::endHavingGroup
     * @covers ::addHavingOperatorCondition
     * @covers ::startHavingConditionGroup
     * @covers ::addHavingPlaceholder
     */
    public function testHavingMethods()
    {
        $this->conditionMethodsTest('having');
    }
    

}