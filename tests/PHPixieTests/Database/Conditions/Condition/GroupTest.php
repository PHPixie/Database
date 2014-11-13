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
        $expected = array();
        $this->condition->add($expected[] = $this->condition());
        $this->condition->add($expected[] = $this->condition());
        $this->condition->add($expected[] = $this->condition());

        $conditions = $this->condition->conditions();
        $this->assertEquals($expected, $conditions);
    }

    protected function condition()
    {
        return new \PHPixie\Database\Conditions\Condition\Operator('a', '=', 1);
    }
}
