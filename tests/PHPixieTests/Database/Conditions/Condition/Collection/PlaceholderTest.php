<?php

namespace PHPixieTests\Database\Conditions\Condition\Collection;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Condition\Collection\Placeholder
 */
class PlaceholderTest extends \PHPixieTests\Database\Conditions\Condition\ImplementationTest
{
    protected $container;

    public function setUp()
    {
        $this->container = $this->getContainer();
        parent::setUp();
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }

    
    /**
     * @covers ::container
     * @covers ::<protected>
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
        return new \PHPixie\Database\Conditions\Condition\Collection\Placeholder($this->container, $allowEmpty);   
    }
    
    protected function condition()
    {
        return $this->placeholder();
    }
}
