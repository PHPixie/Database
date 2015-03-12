<?php

namespace PHPixie\Tests\Database\Conditions\Builder;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Builder\Container
 */
abstract class ContainerTest extends \PHPixie\Tests\AbstractDatabaseTest
{
    protected $container;    

    public function setUp()
    {
        $this->container = $this->container();
    }
    
    /**
     * @covers \PHPixie\Database\Conditions\Builder\Container::__construct
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
     * @covers ::buildCondition
     * @covers ::getConditions
     * @covers ::addCondition
     * @covers ::pushGroup
     * @covers ::<protected>
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
     * @covers ::buildCondition
     * @covers ::getConditions
     */
    public function testAddCondition()
    {
        $condition = $this->conditions->operator('pixie', '>', array(2));
            
        $this->container
                    ->addCondition('or', true, $condition)
                    ->buildCondition('and', false, array('a', 1))
                    ->buildCondition('or', false, array('a', '>', 1))
                    ->buildCondition('xor', false, array('a', 1))
                    ->buildCondition('and', true, array('a', 1))
                    ->buildCondition('or', true, array('a', 1))
                    ->buildCondition('xor', true, array('a', 1))
                    ->buildCondition('or', false, array(function ($container) {
                        $container->_or('a', 1);
                    }))
                    ->startConditionGroup('and', true)
                        ->buildCondition('and', true, array('a', 1))
                    ->endGroup();

        $this->assertConditions(array(
            array('or', true, 'pixie', '>', array(2)),
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
     * @covers ::endGroup
     * @covers ::<protected>
     */
    public function testStack()
    {
        $this->container
            ->_and(function($b){
                $b->_and(function($b){
                    $b->_and('a', 1);
                })
                ->_and('b', 1);
            })
            ->_and('c', 1);

        $this->assertConditions(array(
            array('and', false, array(
                    array('and', false, array(
                        array('and', false, 'a', '=', array(1))
                    )),
                    array('and', false, 'b', '=', array(1))
                )
            ),
            array('and', false, 'c', '=', array(1))
        ));
    }

    /**
     * @covers ::buildCondition
     */
    public function testAddConditionException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->buildCondition('and', true, array());
    }

    /**
     * @covers ::addOperatorCondition
     * @covers ::getConditions
     * @covers ::addCondition
     */
    public function testAddOperatorCondition()
    {
        $return = $this->container
            ->addOperatorCondition('or', true, 'test', '>', array(5))
            ->addOperatorCondition('or', true, 'pixie', '<', array(5));
        
        $this->assertConditions(array(
            array('or', true, 'test', '>', array(5)),
            array('or', true, 'pixie', '<', array(5))
        ));
    }

    /**
     * @covers ::addOperatorCondition
     * @covers ::startConditionGroup
     * @covers ::endGroup
     * @covers ::getConditions
     * @covers ::<protected>
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
     * @covers ::<protected>
     */
    public function testAddPlaceholder()
    {
        $container = $this->container->addPlaceholder();
        $this->assertPlaceholder($container, 'and', false, true);

        $container = $this->container->addPlaceholder('or', true, false);
        $this->assertPlaceholder($container, 'or', true, false);
    }

    /**
     * @covers ::startConditionGroup
     * @covers ::<protected>
     */
    public function testStartConditionGroupException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->startConditionGroup('test');
    }

    /**
     * @covers ::endGroup
     * @covers ::<protected>
     */
    public function testEndGroupException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->endGroup();
    }

    /**
     * @covers ::endGroup
     * @covers ::<protected>
     */
    public function testNestedEndGroupException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->startGroup('and')->endGroup()->endGroup();
    }

    /**
     * @covers ::buildCondition
     * @covers ::<protected>
     */
    public function testSingleArgumentException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->_and('a');
    }

    /**
     * @covers ::buildCondition
     * @covers ::<protected>
     */
    public function testNoArgumentsException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->container->_and();
    }
    
    protected function operatorTest($method, $params, $operatorParams)
    {
        $callback = array($this->container, $method);
        
        $this->assertSame($this->container, call_user_func_array($callback, $params));
        $fullParams = array(array_merge(array('and', false), $operatorParams));
        $this->assertConditions($fullParams);
        
        $params[]= 'or';
        $params[]= true;
        $this->assertSame($this->container, call_user_func_array($callback, $params));
        $fullParams[]= array_merge(array('or', true), $operatorParams);
        $this->assertConditions($fullParams);
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
            $this->assertEquals($e[1], $condition->isNegated());
            
            $this->assertCondition($condition, $e);
        }
    }
    
    protected function assertCondition($condition, $expected)
    {
         if ($condition instanceof \PHPixie\Database\Conditions\Condition\Field\Operator) {
                $this->assertEquals($expected[2], $condition->field());
                $this->assertEquals($expected[3], $condition->operator());
                $this->assertEquals($expected[4], $condition->values());
        } else {
                $this->assertConditionArray($condition->conditions(), $expected[2]);
        }
    }
    
    protected function assertPlaceholder($container, $logic, $negated, $allowEmpty)
    {
        $placeholder = $this->getLastCondition();
        $this->assertSame($placeholder->container(), $container);
        $this->assertEquals($logic, $placeholder->logic());
        $this->assertEquals($negated, $placeholder->isNegated());
        $this->assertAttributeEquals($allowEmpty, 'allowEmpty', $placeholder);
    }
    
    protected function getLastCondition()
    {
        $conditions = $this->container->getConditions();
        return end($conditions);
    }
    
    abstract protected function container($defaultOperator = '=');
}
