<?php

namespace PHPixieTests\Database\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Condition\Placeholder
 */
class PlaceholderTest extends \PHPixieTests\Database\Conditions\ConditionTest
{
    protected $conditionsMock;
    protected $containerMock;

    protected function setUp()
    {
        $this->containerMock = $this->quickMock('\PHPixie\Database\Conditions\Builder\Container', array('getConditions'));
        $this->conditionsMock = $this->quickMock('\PHPixie\Database\Conditions', array('container', 'group'));
        $this->condition = new \PHPixie\Database\Conditions\Condition\Placeholder($this->conditionsMock, '=');
    }

    /**
     * @covers ::container
     * @covers ::__construct
     */
    public function testContainer()
    {
        $this->expectContainer();
        $this->assertEquals($this->containerMock, $this->condition->container());
        $this->assertEquals($this->containerMock, $this->condition->container());
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
        $this->expectCalls($this->containerMock, array(), array('getConditions' => array()));
        $placeholder = new \PHPixie\Database\Conditions\Condition\Placeholder($this->conditionsMock, '>', false);
        $this->expectContainer('>');
        $placeholder->container();
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
        $this->expectCalls($this->containerMock, array(), array('getConditions' => array('test')));
        $this->expectContainer();
        $this->condition->container();
        $this->assertEquals(array('test'), $this->condition->conditions());
    }

    protected function expectContainer($operator = '=')
    {
        $this->conditionsMock
                ->expects($this->once())
                ->method('container')
                ->with($operator)
                ->will($this->returnValue($this->containerMock));
    }
}
