<?php

namespace PHPixieTests\DB;

/**
 * @coversDefaultClass \PHPixie\DB\Conditions
 */
class ConditionsTest extends \PHPixieTests\AbstractDBTest
{
    protected $conditions;

    public function setUp()
    {
        $this->conditions = new \PHPixie\DB\Conditions;
    }

    /**
     * @covers ::operator
     */
    public function testOperator()
    {
        $operator = $this->conditions->operator('a', '=', array(1));
        $this->assertInstanceOf('PHPixie\DB\Conditions\Condition\Operator', $operator);
        $this->assertEquals('a', $operator->field);
        $this->assertEquals('=', $operator->operator);
        $this->assertEquals(array(1), $operator->values);
    }

    /**
     * @covers ::group
     */
    public function testGroup()
    {
        $group = $this->conditions->group();
        $this->assertInstanceOf('PHPixie\DB\Conditions\Condition\Group', $group);
    }

    /**
     * @covers ::placeholder
     */
    public function testPlaceholder()
    {
        $placeholder = $this->conditions->placeholder();
        $this->assertInstanceOf('PHPixie\DB\Conditions\Condition\Placeholder', $placeholder);
        $this->assertAttributeEquals('=', 'defaultOperator', $placeholder->builder());
        
        $placeholder = $this->conditions->placeholder('>');
        $this->assertAttributeEquals('>', 'defaultOperator', $placeholder->builder());
    }
    
    /**
     * @covers ::builder
     */
    public function testBuilder()
    {
        $builder = $this->conditions->builder();
        $this->assertInstanceOf('PHPixie\DB\Conditions\Builder', $builder);
        $this->assertAttributeEquals('=', 'defaultOperator', $builder);
        $builder = $this->conditions->builder('>');
        $this->assertAttributeEquals('>','defaultOperator',$builder);
    }

}
