<?php

namespace PHPixie\DB\Query\Condition;

abstract class Group extends PHPixie\DB\Query\Condition {
	
	protected $and_conditions = array();
	protected $or_conditions = array();
	
	public function add_and($condition) {
		$this->and_conditions[] = $condition;
	}
	
	public function add_or($condition) {
		$this->or_conditions[] = $condition;
	}
	
	public function add($operator, $condition){
		switch($operator) {
			'and':
				$this->add_and($condition);
				break;
			'or':
				$this->add_or($condition);
				break;
			default:
				throw new \Exception("Operator must be either 'and', 'or'");
		}
	}
}