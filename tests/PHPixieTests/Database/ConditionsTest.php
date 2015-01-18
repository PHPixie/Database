<?php

namespace PHPixieTests\Database;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions
 */
class ConditionsTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $conditions;
    protected $operatorClass    = '\PHPixie\Database\Conditions\Condition\Field\Operator';
    protected $groupClass       = '\PHPixie\Database\Conditions\Condition\Collection\Group';
    protected $placeholderClass = '\PHPixie\Database\Conditions\Condition\Collection\Placeholder';
    protected $containerClass   = '\PHPixie\Database\Conditions\Builder\Container';

    public function setUp()
    {
        $this->conditions = $this->conditions();
    }

    /**
     * @covers ::operator
     */
    public function testOperator()
    {
        $operator = $this->conditions->operator('a', '=', array(1));
        $this->assertInstanceOf($this->operatorClass, $operator);
        $this->assertEquals('a', $operator->field());
        $this->assertEquals('=', $operator->operator());
        $this->assertEquals(array(1), $operator->values());
    }

    /**
     * @covers ::group
     */
    public function testGroup()
    {
        $group = $this->conditions->group();
        $this->assertInstanceOf($this->groupClass, $group);
    }

    /**
     * @covers ::placeholder
     */
    public function testPlaceholder()
    {
        $placeholder = $this->conditions->placeholder();
        $this->assertInstanceOf($this->placeholderClass, $placeholder);
        $this->assertAttributeEquals('=', 'defaultOperator', $placeholder->container());

        $placeholder = $this->conditions->placeholder('>');
        $this->assertAttributeEquals('>', 'defaultOperator', $placeholder->container());
    }

    /**
     * @covers ::container
     */
    public function testContainer()
    {
        $container = $this->conditions->container();
        $this->assertInstanceOf($this->containerClass, $container);
        $this->assertAttributeEquals($this->conditions, 'conditions', $container);
        $this->assertAttributeEquals('=', 'defaultOperator', $container);
        
        $container = $this->conditions->container('>');
        $this->assertAttributeEquals('>','defaultOperator',$container);
    }
    
    protected function conditions()
    {
        return new \PHPixie\Database\Conditions();
    }
}
