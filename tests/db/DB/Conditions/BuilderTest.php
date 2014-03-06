<?php

class BuilderTest extends PHPUnit_Framework_TestCase
{
    protected $builder;
    protected $pixie;

    public function setUp()
    {
        $this->pixie = new \PHPixie\Pixie;
        $this->pixie-> db = new \PHPixie\DB($this->pixie);
        $this->builder = new \PHPixie\DB\Conditions\Builder($this->pixie->db, '=');
    }

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

    public function testAddConditions()
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

    public function testAddOperatorCondition()
    {
        throw new \Exception('todo');
    }

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
                                    ->startGroup('xor_not')
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

    public function testStartGroupException()
    {
        $this->assertException(function () {
            $this->builder->startGroup('test');
        });
    }

    public function testEndGroupException()
    {
        $this->assertException(function () {
            $this->builder->endGroup();
        });
    }

    public function testNestedEndGroupException()
    {
        $this->assertException(function () {
            $this->builder->startGroup('and')->endGroup()->endGroup();
        });
    }

    public function testSingleArgumentException()
    {
        $this->assertException(function () {
            $this->builder->_and('a');
        });
    }

    public function noArgumentsException()
    {
        $this->assertException(function () {
            $this->builder->_and();
        });
    }

    protected function assertException($callback)
    {
        $except = false;
        try {
            $callback();
        } catch (\PHPixie\DB\Exception $e) {
            $except = true;
        }

        $this->assertEquals(true, $except);
    }

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
