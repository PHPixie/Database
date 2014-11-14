<?php

namespace PHPixieTests\Database\Conditions\Builder;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Builder\Container
 */
class ContainerTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $container;
    protected $conditions;
    

    public function setUp()
    {
        $this->conditions = $this->conditions();
        $this->container = $this->container();
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
    
    }

    /**
     * @covers ::__construct
     * @covers ::__call
     * @covers ::_and
     * @covers ::_or
     * @covers ::_xor
     * @covers ::_not
     * @covers ::andNot
     * @covers ::orNot
     * @covers ::xorNot
     * @covers ::startGroup
     * @covers ::startAndGroup
     * @covers ::startOrGroup
     * @covers ::startXorGroup
     * @covers ::startNotGroup
     * @covers ::startAndNotGroup
     * @covers ::startOrNotGroup
     * @covers ::startXorNotGroup
     * @covers ::addCondition
     * @covers ::getConditions
     * @covers ::addToCurrentGroup
     * @covers ::pushGroup
     */
    public function testConditions()
    {
        $this->container
                    ->and('a', 1)
                    ->or('a', '>', 1)
                    ->xor('a', 1)
                    ->not('a', 1)
                    ->andNot('a', 1)
                    ->orNot('a', 1)
                    ->xorNot('a', 1)
                    ->or(function ($container) {
                        $container->_or('a', 1);
                    })
                    ->andNot(function ($container) {
                        $container->andNot('a', 1);
                    })
                    ->startGroup()->endGroup()
                    ->startAndGroup()->endGroup()
                    ->startOrGroup()->endGroup()
                    ->startXorGroup()->endGroup()
                    ->startNotGroup()->endGroup()
                    ->startAndNotGroup()->endGroup()
                    ->startOrNotGroup()->endGroup()
                    ->startXorNotGroup()->endGroup();

        $this->assertConditions(array(
            array('and', false, 'a', '=', array(1)),
            array('or', false, 'a', '>', array(1)),
            array('xor', false, 'a', '=', array(1)),
            array('and', true, 'a', '=', array(1)),
            array('and', true, 'a', '=', array(1)),
            array('or', true, 'a', '=', array(1)),
            array('xor', true, 'a', '=', array(1)),
            array('or', false, array(
                    array('or', false, 'a', '=', array(1))
                )
            ),
            array('and', true, array(
                    array('and', true, 'a', '=', array(1))
                )
            ),
            array('and', false, array()),
            array('and', false, array()),
            array('or',  false, array()),
            array('xor', false, array()),
            array('and', true, array()),
            array('and', true, array()),
            array('or',  true, array()),
            array('xor', true, array()),
    
        ));
    }

    /**
     * @covers ::__call
     */
    public function testMethodException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->test();
    }
    
    /**
     * @covers ::addCondition
     * @covers ::getConditions
     * @covers ::addToCurrentGroup
     */
    public function testAddCondition()
    {
        $this->container
                    ->addCondition('and', false, array('a', 1))
                    ->addCondition('or', false, array('a', '>', 1))
                    ->addCondition('xor', false, array('a', 1))
                    ->addCondition('and', true, array('a', 1))
                    ->addCondition('or', true, array('a', 1))
                    ->addCondition('xor', true, array('a', 1))
                    ->addCondition('or', false, array(function ($container) {
                        $container->_or('a', 1);
                    }))
                    ->startConditionGroup('and', true)
                        ->addCondition('and', true, array('a', 1))
                    ->endGroup();

        $this->assertConditions(array(
            array('and', false, 'a', '=', array(1)),
            array('or', false, 'a', '>', array(1)),
            array('xor', false, 'a', '=', array(1)),
            array('and', true, 'a', '=', array(1)),
            array('or', true, 'a', '=', array(1)),
            array('xor', true, 'a', '=', array(1)),
            array('or', false, array(
                    array('or', false, 'a', '=', array(1))
                )
            ),
            array('and', true, array(
                    array('and', true, 'a', '=', array(1))
                )
            )
        ));
    }

    /**
     * @covers ::addCondition
     */
    public function testAddConditionException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->addCondition('and', true, array());
    }

    /**
     * @covers ::addOperatorCondition
     * @covers ::getConditions
     * @covers ::addToCurrentGroup
     */
    public function testAddOperatorCondition()
    {
        $this->container->addOperatorCondition('or', true, 'test', '>', array(5));
        $this->assertConditions(array(
            array('or', true, 'test', '>', array(5))
        ));
    }

    /**
     * @covers ::addOperatorCondition
     * @covers ::startConditionGroup
     * @covers ::endGroup
     * @covers ::getConditions
     * @covers ::addToCurrentGroup
     * @covers ::pushGroup
     */
    public function testNested()
    {
        $this->container
                    ->_and('a', 1)
                    ->_or(function ($container) {
                        $container
                            ->_and('a', 2)
                            ->_or(function ($container) {
                                $container
                                    ->_and('a', 3)
                                    ->startXorNotGroup()
                                        ->_and('a', 4)
                                    ->endGroup();
                            });
                    });

        $this->assertConditions(array(
            array('and', false, 'a', '=', array(1)),
            array('or', false, array(
                array('and', false, 'a', '=', array(2)),
                array('or', false, array(
                    array('and', false, 'a', '=', array(3)),
                    array('xor', true, array(
                        array('and', false, 'a', '=', array(4)),
                    ))
                ))
            ))
        ));
    }

    /**
     * @covers ::addPlaceholder
     */
    public function testAddPlaceholder()
    {
        $placeholder = $this->container->addPlaceholder();
        $this->assertEquals('and', $placeholder->logic());
        $this->assertEquals(false, $placeholder->negated());
        $this->assertAttributeEquals(true, 'allowEmpty', $placeholder);

        $placeholder = $this->container->addPlaceholder('or', true, false);
        $this->assertEquals('or', $placeholder->logic());
        $this->assertEquals(true, $placeholder->negated());
        $this->assertAttributeEquals(false, 'allowEmpty', $placeholder);
    }
    
    /**
     * @covers ::addToCurrentGroup
     */
    public function testAddToCurrentGroup()
    {
        $condition = $this->conditions->operator('a', '=', array(1));
        $this->container->addToCurrentGroup('or', true, $condition);
        $this->assertSame(array($condition), $this->container->getConditions());
        $this->assertSame('or', $condition->logic());
        $this->assertSame(true, $condition->negated());
    }
    
    /**
     * @covers ::startConditionGroup
     */
    public function testStartConditionGroupException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->startConditionGroup('test');
    }

    /**
     * @covers ::endGroup
     */
    public function testEndGroupException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->endGroup();
    }

    /**
     * @covers ::endGroup
     */
    public function testNestedEndGroupException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->startGroup('and')->endGroup()->endGroup();
    }

    /**
     * @covers ::addCondition
     * @covers ::addToCurrentGroup
     */
    public function testSingleArgumentException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->_and('a');
    }

    /**
     * @covers ::addCondition
     * @covers ::addToCurrentGroup
     */
    public function testNoArgumentsException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->_and();
    }

    protected function assertConditions($expected)
    {
        $this->assertConditionArray($this->container->getConditions(), $expected);
    }

    protected function assertConditionArray($conditions, $expected)
    {
        foreach ($conditions as $key => $condition) {
            $e = $expected[$key];

            $this->assertEquals($e[0], $condition->logic());
            $this->assertEquals($e[1], $condition->negated());
            
            $this->assertCondition($condition, $e);
        }
    }
    
    protected function assertCondition($condition, $expected)
    {
         if ($condition instanceof \PHPixie\Database\Conditions\Condition\Operator) {
                $this->assertEquals($expected[2], $condition->field);
                $this->assertEquals($expected[3], $condition->operator);
                $this->assertEquals($expected[4], $condition->values);
        } else {
                $this->assertConditionArray($condition->conditions(), $expected[2]);
        }
    }
    
    protected function conditions()
    {
        return new \PHPixie\Database\Conditions;
    }
    
    protected function container($defaultOperator = '=')
    {
        return new \PHPixie\Database\Conditions\Builder\Container($this->conditions, $defaultOperator);
    }
}
