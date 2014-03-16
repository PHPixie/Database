<?php

namespace PHPixieTests\Database\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Condition\Placeholder
 */
class PlaceholderTest extends \PHPixieTests\Database\Conditions\ConditionTest
{
    protected $conditionsMock;
    protected $builderMock;

    protected function setUp()
    {
        $this->builderMock = $this->quickMock('\PHPixie\Database\Conditions\Builder', array('getConditions'));
        $this->conditionsMock = $this->quickMock('\PHPixie\Database\Conditions', array('builder', 'group'));
        $this->condition = new \PHPixie\Database\Conditions\Condition\Placeholder($this->conditionsMock, '=');
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
        $placeholder = new \PHPixie\Database\Conditions\Condition\Placeholder($this->conditionsMock, '=', false);
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $placeholder->conditions();
    }

    /**
     * @covers ::conditions
     */
    public function testConditionsEmptyException()
    {
        $this->expectCalls($this->builderMock, array(), array('getConditions' => array()));
        $placeholder = new \PHPixie\Database\Conditions\Condition\Placeholder($this->conditionsMock, '>', false);
        $this->expectBuilder('>');
        $placeholder->builder();
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $placeholder->conditions();
    }

    /**
     * @covers ::conditions
     */
    public function testConditionsEmpty()
    {
        $this->condition = new \PHPixie\Database\Conditions\Condition\Placeholder($this->conditionsMock);
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

    protected function expectBuilder($operator = '=')
    {
        $this->conditionsMock
                ->expects($this->once())
                ->method('builder')
                ->with($operator)
                ->will($this->returnValue($this->builderMock));
    }
}
