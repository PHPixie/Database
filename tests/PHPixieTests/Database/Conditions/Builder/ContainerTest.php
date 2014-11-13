<?php

namespace PHPixieTests\Database\Conditions\Builder;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Builder\Container
 */
class ContainerTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $builder;
    protected $conditions;
    

    public function setUp()
    {
        $this->conditions = new \PHPixie\Database\Conditions;
        $this->builder = new \PHPixie\Database\Conditions\Builder\Container($this->conditions, '=');
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
     * @covers ::addToCurrent
     * @covers ::pushGroup
     */
    public function testConditions()
    {
        $this->builder
                    ->and('a', 1)
                    ->or('a', '>', 1)
                    ->xor('a', 1)
                    ->not('a', 1)
                    ->andNot('a', 1)
                    ->orNot('a', 1)
                    ->xorNot('a', 1)
                    ->or(function ($builder) {
                        $builder->_or('a', 1);
                    })
                    ->andNot(function ($builder) {
                        $builder->andNot('a', 1);
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
        $this->builder->test();
    }
    
    /**
     * @covers ::addCondition
     * @covers ::getConditions
     * @covers ::addToCurrent
     */
    public function testAddCondition()
    {
        $this->builder
                    ->addCondition('and', false, array('a', 1))
                    ->addCondition('or', false, array('a', '>', 1))
                    ->addCondition('xor', false, array('a', 1))
                    ->addCondition('and', true, array('a', 1))
                    ->addCondition('or', true, array('a', 1))
                    ->addCondition('xor', true, array('a', 1))
                    ->addCondition('or', false, array(function ($builder) {
                        $builder->_or('a', 1);
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
        $this->builder->addCondition('and', true, array());
    }

    /**
     * @covers ::addOperatorCondition
     * @covers ::getConditions
     * @covers ::addToCurrent
     */
    public function testAddOperatorCondition()
    {
        $this->builder->addOperatorCondition('or', true, 'test', '>', array(5));
        $this->assertConditions(array(
            array('or', true, 'test', '>', array(5))
        ));
    }

    /**
     * @covers ::addOperatorCondition
     * @covers ::startConditionGroup
     * @covers ::endGroup
     * @covers ::getConditions
     * @covers ::addToCurrent
     * @covers ::pushGroup
     */
    public function testNested()
    {
        $this->builder
                    ->_and('a', 1)
                    ->_or(function ($builder) {
                        $builder
                            ->_and('a', 2)
                            ->_or(function ($builder) {
                                $builder
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
        $placeholder = $this->builder->addPlaceholder();
        $this->assertEquals('and', $placeholder->logic());
        $this->assertEquals(false, $placeholder->negated());
        $this->assertAttributeEquals(true, 'allowEmpty', $placeholder);

        $placeholder = $this->builder->addPlaceholder('or', true, false);
        $this->assertEquals('or', $placeholder->logic());
        $this->assertEquals(true, $placeholder->negated());
        $this->assertAttributeEquals(false, 'allowEmpty', $placeholder);
    }

    /**
     * @covers ::startConditionGroup
     */
    public function testStartConditionGroupException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->builder->startConditionGroup('test');
    }

    /**
     * @covers ::endGroup
     */
    public function testEndGroupException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->builder->endGroup();
    }

    /**
     * @covers ::endGroup
     */
    public function testNestedEndGroupException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->builder->startGroup('and')->endGroup()->endGroup();
    }

    /**
     * @covers ::addCondition
     * @covers ::addToCurrent
     */
    public function testSingleArgumentException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->builder->_and('a');
    }

    /**
     * @covers ::addCondition
     * @covers ::addToCurrent
     */
    public function testNoArgumentsException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->builder->_and();
    }

    protected function assertConditions($expected)
    {
        $this->assertConditionArray($this->builder->getConditions(), $expected);
    }

    protected function assertConditionArray($conditions, $expected)
    {
        foreach ($conditions as $key => $condition) {
            $e = $expected[$key];

            $this->assertEquals($e[0], $condition->logic());
            $this->assertEquals($e[1], $condition->negated());
            if ($condition instanceof \PHPixie\Database\Conditions\Condition\Operator) {
                $this->assertEquals($e[2], $condition->field);
                $this->assertEquals($e[3], $condition->operator);
                $this->assertEquals($e[4], $condition->values);
            } else {
                $this->assertConditionArray($condition->conditions(), $e[2]);
            }
        }
    }
}
