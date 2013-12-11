<?php

namespace PHPixie\DB\Conditions;

class Builder {
	
	protected $db;
	protected $group_stack = array();
	protected $current_group;
	protected $default_operator;
	
	public function __construct($db, $default_operator = '='){
		$this->db = $db;
		$this->default_operator = $default_operator;
		$this->current_group = $this->db->condition_group();
		$this->group_stack[] = $this->current_group;
		
	}
	
	
	public function start_group($logic = 'and') {
		$group = $this->db->condition_group();
		switch($logic) {
			case 'and':
			case 'or':
			case 'xor':
				$this->current_group->add($group, $logic);
				break;
				
			case 'and_not':
			case 'or_not':
			case 'xor_not':
				$logic = substr($logic, 0, -4);
				$this->current_group->add($group->negate(), $logic);
				break;
			default:
				throw new \PHPixie\DB\Exception("Operator must be either 'and', 'or', 'xor', 'and_not' ,'or_not', 'xor_not'");
		}
		$this->group_stack[]=$group;
		$this->current_group = $group;
		return $this;
	}
	
	public function end_group() {
		if (count($this->group_stack) === 1)
			throw new \PHPixie\DB\Exception("End() was called more times than expected.");
			
		array_pop($this->group_stack);
		$this->current_group = current($this->group_stack);
		return $this;
	}
	
	public function add_condition($logic, $negate, $args) {
		$count = count($args);
		if ($count >= 2) {
		
			if ($count === 2) {
				
				$condition = $this->db->operator($args[0], $this->default_operator, array($args[1]));
			}else {
				$condition = $this->db->operator($args[0], $args[1],  array_slice($args, 2));
			}
			
			if ($negate)
				$condition->negate();
				
			$this->current_group->add($condition, $logic);
			return $this;
		}
		
		if ($count === 1)
			if (is_callable($callback = $args[0])) {
				if ($negate)
					$logic = $logic.'_not';
				$this->start_group($logic);
				$callback($this);
				$this->end_group();
				return $this;
			}else 
				throw new \PHPixie\DB\Exception("If only one argument is provided it must be a callable");
		
		throw new \PHPixie\DB\Exception("Not enough arguments provided");
	}
	
	public function get_conditions() {
		return $this->group_stack[0]->conditions();
	}
	
	public function _and() {
		return $this->add_condition('and', false, func_get_args());
	}
	
	public function _or() {
		return $this->add_condition('or', false, func_get_args());
	}
	
	public function _xor() {
		return $this->add_condition('xor', false, func_get_args());
	}
	
	public function _and_not() {
		return $this->add_condition('and', true, func_get_args());
	}
	
	public function _or_not() {
		return $this->add_condition('or', true, func_get_args());
	}
	
	public function _xor_not() {
		return $this->add_condition('xor', true, func_get_args());
	}
}