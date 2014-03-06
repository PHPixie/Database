<?php

require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/Conditions/ConditionTest.php');

class GroupTest extends ConditionTest
{
    protected function setUp()
    {
        $this->condition = new PHPixie\DB\Conditions\Condition\Group();
    }

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

    public function testSetConditions()
    {
        throw new \Exception("Not implemented");
    }

    public function testException()
    {
        $except = false;
        try {
            $this->condition->add($expected[] = $this->condition(), 'maybe');
        } catch (\PHPixie\DB\Exception $e) {
            $except = true;
        }

        $this->assertEquals(true, $except);
    }

    public function condition()
    {
        return new PHPixie\DB\Conditions\Condition\Operator('a', '=', 1);
    }
}
