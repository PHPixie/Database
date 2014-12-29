<?php

namespace PHPixieTests\Database\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Condition\Placeholder
 */
class PlaceholderTest extends \PHPixieTests\Database\Conditions\ConditionTest
{
    protected $container;

    protected function setUp()
    {
        $this->container = $this->getContainer();
        $this->condition = $this->placeholder();
    }

    /**
     * @covers ::container
     * @covers ::__construct
     */
    public function testContainer()
    {
        $this->assertEquals($this->container, $this->condition->container());
        $this->assertEquals($this->container, $this->condition->container());
    }

    /**
     * @covers ::conditions
     */
    public function testConditionsEmptyException()
    {
        $this->expectCalls($this->container, array(), array('getConditions' => array()));
        $placeholder = $this->placeholder(false);
        $placeholder->container();
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $placeholder->conditions();
    }

    /**
     * @covers ::conditions
     */
    public function testConditionsEmpty()
    {
        $this->expectCalls($this->container, array(), array('getConditions' => array()));
        $this->assertEquals(array(), $this->condition->conditions());
    }

    /**
     * @covers ::conditions
     */
    public function testConditions()
    {
        $this->expectCalls($this->container, array(), array('getConditions' => array('test')));
        $this->condition->container();
        $this->assertEquals(array('test'), $this->condition->conditions());
    }
    
    protected function getContainer()
    {
        return $this->quickMock('\PHPixie\Database\Conditions\Builder\Container', array('getConditions'));
    }
    
    protected function placeholder($allowEmpty = true)
    {
        return new \PHPixie\Database\Conditions\Condition\Placeholder($this->container, $allowEmpty);   
    }
}
