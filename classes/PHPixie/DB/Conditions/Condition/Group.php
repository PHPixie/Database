<?php

namespace PHPixie\DB\Conditions\Condition;

class Group extends \PHPixie\DB\Conditions\Condition {
	
	protected $conditions = array();
	protected $allowed_logic = array('and', 'or', 'xor');
	
	public function add_and($condition) {
		$this->add($condition, 'and');
	}
	
	public function add_or($condition) {
		$this->add($condition, 'or');
	}
	
	public function add_xor($condition) {
		$this->add($condition, 'xor');
	}
	
	public function add($condition, $logic = 'and') {
		if (!in_array($logic, $this->allowed_logic))
			throw new \PHPixie\DB\Exception("The '$logic' logic is not supported");
			
		$condition->logic = $logic;
		$this->conditions[] = $condition;
	}
	
	public function conditions(){
		return $this->conditions;
	}
	
	public function set_conditions($conditions) {
		$this->conditions = $conditions;
	}
}