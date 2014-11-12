<?php

namespace PHPixieTests\Database\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Condition\Operator
 */
class OperatorTest extends \PHPixieTests\Database\Conditions\ConditionTest
{
    protected function setUp()
    {
        $this->condition = new \PHPixie\Database\Conditions\Condition\Operator('a', '=', array(1));
    }

    public function testProperties()
    {
        $this->assertEquals('a', $this->condition->field);
        $this->assertEquals('=', $this->condition->operator);
        $this->assertEquals(array(1), $this->condition->values);
    }
}
