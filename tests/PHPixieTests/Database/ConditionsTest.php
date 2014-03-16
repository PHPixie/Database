<?php

namespace PHPixieTests\Database;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions
 */
class ConditionsTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $conditions;

    public function setUp()
    {
        $this->conditions = new \PHPixie\Database\Conditions;
    }

    /**
     * @covers ::operator
     */
    public function testOperator()
    {
        $operator = $this->conditions->operator('a', '=', array(1));
        $this->assertInstanceOf('PHPixie\Database\Conditions\Condition\Operator', $operator);
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
        $this->assertInstanceOf('PHPixie\Database\Conditions\Condition\Group', $group);
    }

    /**
     * @covers ::placeholder
     */
    public function testPlaceholder()
    {
        $placeholder = $this->conditions->placeholder();
        $this->assertInstanceOf('PHPixie\Database\Conditions\Condition\Placeholder', $placeholder);
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
        $this->assertInstanceOf('PHPixie\Database\Conditions\Builder', $builder);
        $this->assertAttributeEquals('=', 'defaultOperator', $builder);
        $builder = $this->conditions->builder('>');
        $this->assertAttributeEquals('>','defaultOperator',$builder);
    }

}
