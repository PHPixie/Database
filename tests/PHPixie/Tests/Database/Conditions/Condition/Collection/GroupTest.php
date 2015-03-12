<?php

namespace PHPixie\Tests\Database\Conditions\Condition\Collection;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Condition\Collection\Group
 */
class GroupTest extends \PHPixie\Tests\Database\Conditions\Condition\ImplementationTest
{
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
        $this->condition->add($expected[] = $this->operatorCondition());
        $this->condition->add($expected[] = $this->operatorCondition());
        $this->condition->add($expected[] = $this->operatorCondition());

        $conditions = $this->condition->conditions();
        $this->assertEquals($expected, $conditions);
    }

    protected function operatorCondition()
    {
        return new \PHPixie\Database\Conditions\Condition\Field\Operator('a', '=', 1);
    }
    
    protected function condition()
    {
        return new \PHPixie\Database\Conditions\Condition\Collection\Group();
    }
}
