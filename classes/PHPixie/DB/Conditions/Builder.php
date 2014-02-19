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
	
	public function start_group($logic = 'and', $negate = false) {
		$group = $this->condition_group();
		$this->push_group($logic, $negate, $group);
		return $this;
	}
	
	protected function push_group($logic, $negate, $group) {
		$this->add_subgroup($logic, $negate, $group, $this->current_group);
		$this->group_stack[]=$group;
		$this->current_group = $group;
	}
	
	protected function add_subgroup($logic, $negate, $group, $parent) {
		switch($logic) {
			case 'and':
			case 'or':
			case 'xor':
				break;
			case 'and_not':
			case 'or_not':
			case 'xor_not':
				$logic = substr($logic, 0, -4);
				$negate = !$negate;
				break;
			default:
				throw new \PHPixie\DB\Exception("Logic must be either 'and', 'or', 'xor', 'and_not' ,'or_not', 'xor_not'");
		}
		
		if ($negate)
			$group->negate();
		
		$parent->add($group, $logic);
	
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
			$field = $args[0];
			
			if ($count === 2) {
				$operator = $this->default_operator;
				$values = array($args[1]);
			}else {
				$operator = $args[1];
				$values = array_slice($args, 2);
			}
			
			$this->add_operator_condition($logic, $negate, $field, $operator, $values);
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
	
	protected function build_operator_condition($negate, $field, $operator, $values) {
		$condition = $this->operator($field, $operator, $values);
		if ($negate)
			$condition->negate();
			
		return $condition;
	}
	
	public function add_operator_condition($logic, $negate, $field, $operator, $values) {
		$condition = $this->build_operator_condition($negate, $field, $operator, $values);
		$this->current_group->add($condition, $logic);
		return $condition;
	}
	
	public function add_placeholder($logic, $negate) {
		//NEEDS TEST
		$placeholder = $this->build_placeholder();
		$this->current_group->add($placeholder, $logic);
	}
	
	protected function build_placeholder() {
		//NEEDS TEST
		return $this->db->condition_placeholder();
	}
	
	protected function operator($field, $operator, $values) {
		return $this->db->operator($field, $operator, $values);
	}
	
	protected function condition_group() {
		return $this->db->condition_group();
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