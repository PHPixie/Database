<?php

namespace PHPixieTest\DB\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\DB\Conditions\Condition\Group
 */
class GroupTest extends PHPixieTest\DB\Conditions\ConditionTest
{
    protected function setUp()
    {
        $this->condition = new PHPixie\DB\Conditions\Condition\Group();
    }

    /**
     * @covers ::addAnd
     * @covers ::addOr
     * @covers ::addXor
     * @covers ::add
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
     */
    public function testSetConditions()
    {
        throw new \Exception("Not implemented");
    }

    /**
     * @covers ::add
     */
    public function testException()
    {
        $this->setExpectedException('\PHPixie\DB\Exception');
        $this->condition->add($expected[] = $this->condition(), 'maybe');
    }

    protected function condition()
    {
        return new PHPixie\DB\Conditions\Condition\Operator('a', '=', 1);
    }
}
