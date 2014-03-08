<?php

namespace PHPixieTests\DB\Conditions;

/**
 * @coversDefaultClass \PHPixie\DB\Conditions\Builder
 */
class BuilderTest extends PHPUnit_Framework_TestCase
{
    protected $builder;
    protected $conditions;

    public function setUp()
    {
        $this->conditions = new \PHPixie\DB\Conditions;
        $this->builder = new \PHPixie\DB\Conditions\Builder($this->conditions, '=');
    }

    /**
     * @covers ::_and
     * @covers ::_or
     * @covers ::_xor
     * @covers ::_andNot
     * @covers ::_orNot
     * @covers ::_xorNot
     * @covers ::addCondition
     */
    public function testConditions()
    {
        $this->builder
                    ->_and('a', 1)
                    ->_or('a', '>', 1)
                    ->_xor('a', 1)
                    ->_andNot('a', 1)
                    ->_orNot('a', 1)
                    ->_xorNot('a', 1)
                    ->_or(function ($builder) {
                        $builder->_or('a', 1);
                    })
                    ->_andNot(function ($builder) {
                        $builder->_andNot('a', 1);
                    });

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
                    ->startGroup('and_not')
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
     * @covers ::addOperatorCondition
     */
    public function testAddOperatorCondition()
    {
        throw new \Exception('todo');
    }

    /**
     * @covers ::addOperatorCondition
     * @covers ::startGroup
     * @covers ::endGroup
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
                                    ->startGroup('xorNot')
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
     * @covers ::startGroup
     */
    public function testStartGroupException()
    {
        $this->setExpectedException('\PHPixie\DB\Exception');
        $this->builder->startGroup('test');
    }

    /**
     * @covers ::endGroup
     */
    public function testEndGroupException()
    {
        $this->setExpectedException('\PHPixie\DB\Exception');
        $this->builder->endGroup();
    }

    /**
     * @covers ::endGroup
     */
    public function testNestedEndGroupException()
    {
        $this->setExpectedException('\PHPixie\DB\Exception');
        $this->builder->startGroup('and')->endGroup()->endGroup();
    }

    /**
     * @covers ::addCondition
     */
    public function testSingleArgumentException()
    {
        $this->setExpectedException('\PHPixie\DB\Exception');
        $this->builder->_and('a');
    }

    /**
     * @covers ::addCondition
     */
    public function noArgumentsException()
    {
        $this->setExpectedException('\PHPixie\DB\Exception');
        $this->builder->_and();
    }

    /**
     * @covers ::getConditions
     */
    protected function assertConditions($expected)
    {
        $this->assertConditionArray($this->builder->getConditions(), $expected);
    }

    protected function assertConditionArray($conditions, $expected)
    {
        foreach ($conditions as $key => $condition) {
            $e = $expected[$key];

            $this->assertEquals($e[0], $condition->logic);
            $this->assertEquals($e[1], $condition->negated());
            if ($condition instanceof \PHPixie\DB\Conditions\Condition\Operator) {
                $this->assertEquals($e[2], $condition->field);
                $this->assertEquals($e[3], $condition->operator);
                $this->assertEquals($e[4], $condition->values);
            } else {
                $this->assertConditionArray($condition->conditions(), $e[2]);
            }
        }
    }
}
