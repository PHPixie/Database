<?php

namespace PHPixieTests\Database\Conditions;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Builder
 */
class BuilderTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $builder;
    protected $conditions;

    public function setUp()
    {
        $this->conditions = new \PHPixie\Database\Conditions;
        $this->builder = new \PHPixie\Database\Conditions\Builder($this->conditions, '=');
    }

    /**
     * @covers ::__construct
     * @covers ::_and
     * @covers ::_or
     * @covers ::_xor
     * @covers ::_andNot
     * @covers ::_orNot
     * @covers ::_xorNot
     * @covers ::addCondition
     * @covers ::getConditions
     * @covers ::addSubgroup
     * @covers ::addToCurrent
     * @covers ::pushGroup
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
     * @covers ::getConditions
     * @covers ::addSubgroup
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
                    ->startGroup('andNot')
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
     * @covers ::addSubgroup
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
     * @covers ::startGroup
     * @covers ::endGroup
     * @covers ::getConditions
     * @covers ::addSubgroup
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
     * @covers ::addPlaceholder
     */
    public function testAddPlaceholder() {
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
     * @covers ::startGroup
     * @covers ::addSubgroup
     */
    public function testStartGroupException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->builder->startGroup('test');
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

            $this->assertEquals($e[0], $condition->logic);
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
