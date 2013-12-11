<?php

require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/Conditions/ConditionTest.php');

class GroupTest extends ConditionTest {

	protected function setUp() {
		$this->condition = new PHPixie\DB\Conditions\Condition\Group();
	}
	
	public function testGroup() { 
		$expected = array();
		$this->condition->add_and($expected[] = $this->condition());
		$this->condition->add_or($expected[] = $this->condition());
		$this->condition->add_xor($expected[] = $this->condition());
		$this->condition->add($expected[] = $this->condition(), 'and');
		$this->condition->add($expected[] = $this->condition(), 'or');
		$this->condition->add($expected[] = $this->condition(), 'xor');
		
		$conditions = $this->condition->conditions();
		$this->assertEquals($expected, $conditions);
		
		$expected_logic = array('and', 'or', 'xor', 'and', 'or', 'xor');
		foreach($conditions as $key => $condition) {
			$this->assertEquals($expected_logic[$key], $condition->logic);
		}
		
	}
	
	
	public function testException() {
		$except = false;
		try {
			$this->condition->add($expected[] = $this->condition(), 'maybe');
		}catch (\PHPixie\DB\Exception $e) {
			$except = true;
		}
		
		$this->assertEquals(true, $except);
	}
	
	public function condition() {
		return new PHPixie\DB\Conditions\Condition\Operator('a', '=', 1);
	}
}