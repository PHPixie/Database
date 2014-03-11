<?php

namespace PHPixieTests\DB\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\DB\Conditions\Condition\Placeholder
 */
class PlaceholderTest extends \PHPixieTests\DB\Conditions\ConditionTest
{
    protected $conditionsMock;
    protected $builderMock;
    
    protected function setUp()
    {
        $this->builderMock = $this->quickMock('\PHPixie\DB\Conditions\Builder', array('getConditions'));
        $this->conditionsMock = $this->quickMock('\PHPixie\DB\Conditions', array('builder', 'group'));
        $this->condition = new \PHPixie\DB\Conditions\Condition\Placeholder($this->conditionsMock, '=');
    }

    /**
     * @covers ::builder
     * @covers ::__construct
     */
    public function testBuilder()
    {
        $this->expectBuilder();
        $this->assertEquals($this->builderMock, $this->condition->builder());
        $this->assertEquals($this->builderMock, $this->condition->builder());
    }
    
    /**
     * @covers ::conditions
     */
    public function testConditionsNoBuilderException()
    {
        $placeholder = new \PHPixie\DB\Conditions\Condition\Placeholder($this->conditionsMock, '=', false);
        $this->setExpectedException('\PHPixie\DB\Exception\Builder');
        $placeholder->conditions();
    }
    
    /**
     * @covers ::conditions
     */
    public function testConditionsEmptyException()
    {
        $this->expectCalls($this->builderMock, array(), array('getConditions' => array()));
        $placeholder = new \PHPixie\DB\Conditions\Condition\Placeholder($this->conditionsMock, '>', false);
        $this->expectBuilder('>');
        $placeholder->builder();
        $this->setExpectedException('\PHPixie\DB\Exception\Builder');
        $placeholder->conditions();
    }
    
    /**
     * @covers ::conditions
     */
    public function testConditionsEmpty()
    {
        $this->condition = new \PHPixie\DB\Conditions\Condition\Placeholder($this->conditionsMock);
        $this->assertEquals(array(), $this->condition->conditions());
    }
    
    /**
     * @covers ::conditions
     */
    public function testConditions()
    {
        $this->expectCalls($this->builderMock, array(), array('getConditions' => array('test')));
        $this->expectBuilder();
        $this->condition->builder();
        $this->assertEquals(array('test'), $this->condition->conditions());
    }
    
    protected function expectBuilder($operator = '=') {
        $this->conditionsMock
                ->expects($this->once())
                ->method('builder')
                ->with($operator)
                ->will($this->returnValue($this->builderMock));
    }
}
