<?php

namespace PHPixieTests\Database\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Condition\Group
 */
class GroupTest extends \PHPixieTests\Database\Conditions\ConditionTest
{
    protected function setUp()
    {
        $this->condition = new \PHPixie\Database\Conditions\Condition\Group();
    }

    /**
     * @covers ::addAnd
     * @covers ::addOr
     * @covers ::addXor
     * @covers ::add
     * @covers ::conditions
     */
    public function testGroup()
    {
        $expected = array();
        $this->condition->addAnd($expected[] = $this->condition());
        $this->condition->addOr($expected[] = $this->condition());
        $this->condition->addXor($expected[] = $this->condition());
        $this->condition->add($expected[] = $this->condition(), 'and');
        $this->condition->add($expected[] = $this->condition(), 'or');
        $this->condition->add($expected[] = $this->condition(), 'xor');

        $conditions = $this->condition->conditions();
        $this->assertEquals($expected, $conditions);

        $expectedLogic = array('and', 'or', 'xor', 'and', 'or', 'xor');
        foreach ($conditions as $key => $condition) {
            $this->assertEquals($expectedLogic[$key], $condition->logic);
        }

    }

    /**
     * @covers ::setConditions
     * @covers ::conditions
     */
    public function testSetConditions()
    {
        $conditions = array('test');
        $this->condition->setConditions($conditions);
        $this->assertEquals($conditions, $this->condition->conditions());
    }

    /**
     * @covers ::add
     */
    public function testException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception');
        $this->condition->add($expected[] = $this->condition(), 'maybe');
    }

    protected function condition()
    {
        return new \PHPixie\Database\Conditions\Condition\Operator('a', '=', 1);
    }
}
