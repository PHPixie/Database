<?php

namespace PHPixie\DB\Conditions;

class Builder{
	
	protected $set;
	protected $group_stack;
	protected $current_group;
	
	public function __construct($conditions){
		$this->conditions = $conditions;
	}
	
	public function where($field, $operator) {
		$params = func_get_args();
		array_splice($params, 0, 2);
		$condition = $this->condition($field, $operator, $params);
		$this->current_group->add_and($condition);
	}
	
	public function or_where($field, $operator) {
		$params = func_get_args();
		array_splice($params, 0, 2);
		$condition = $this->condition($field, $operator, $params);
		$this->current_group->add_or($condition);
	}
	
	protected function start_group($operator = 'and') {
		$group = $this->conditions->group();
		switch($operator) {
			case 'and':
			case 'or':
				$this->current_group->add($operator, $group);
				break;
			case 'and_not':
			case 'or_not':
				$inverse = $this->conditions->inverse($group);
				$operator = str_replace('_not', '', $operator);
				$this->current_group->add($operator, $inverse);
				break;
			default:
				throw new \Exception("Operator must be either 'and', 'or', 'and_not' or 'or_not'");
		}
		$this->group_stack[]=$group;
		$this->current_group = $group;
	}
	
	public function end_group() {
		$this->current_group = array_pop($this->group_stack);
		if ($this->current_group === null)
			throw new \Exception("End() was called more times than expected.");
	}
	
	
	protected function condition($field, $operator, $params = array()){
		
		if (count($params) == 0) {
			$params = $operator;
			$operator = '=';
		}
		
		return $this->set->condition($operator, $field, $params);
	}
}