<?php
namespace PHPixieTests\Database\Driver\PDO\Query\Type;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query\Type\Select;
 */
class SelectTest extends \PHPixieTests\Database\Driver\PDO\Query\ItemTest
{
    protected $queryClass = '\PHPixie\Database\Driver\PDO\Query\Type\Select';
    protected $type = 'select';
    
    /**
     * @covers ::fields
     * @covers ::getFields
     */
    public function testFields()
    {
        $this->testBuilderMethod('fields', array(array('test')), null, 0,$this->query);
        $this->testBuilderMethod('getFields', array(), null, 1,array('test'), array('test'));
    }
    
    /**
     * @covers ::groupBy
     * @covers ::getGroupBy
     */
    public function testGroupBy()
    {
        $this->testBuilderMethod('groupBy', array('test'), null, 0,$this->query);
        $this->testBuilderMethod('getGroupBy', array(), null, 1,array('test'), array('test'));
    }
    
    /**
     * @covers ::union
     * @covers ::getUnions
     */
    public function testUnion()
    {
        $this->testBuilderMethod('union', array('test'), null, 0,$this->query, null, array('test', false));
        $this->testBuilderMethod('union', array('test', true), null, 0,$this->query, null, array('test', true));
        $this->testBuilderMethod('getUnions', array(), null, 1,array('test'), array('test'));
    }

    /**
     * @covers ::<protected>
     * @covers ::getHavingBuilder
     * @covers ::getHavingConditions
     * @covers ::having
     * @covers ::orHaving
     * @covers ::xorHaving
     * @covers ::havingNot
     * @covers ::orHavingNot
     * @covers ::xorHavingNot
     * @covers ::startHavingGroup
     * @covers ::startOrHavingGroup
     * @covers ::startXorHavingGroup
     * @covers ::startHavingNotGroup
     * @covers ::startOrHavingNotGroup
     * @covers ::startXorHavingNotGroup
     * @covers ::endHavingGroup
     */
    public function testHavingMethods()
    {
        $this->testConditionMethods('having');
    }
    

}