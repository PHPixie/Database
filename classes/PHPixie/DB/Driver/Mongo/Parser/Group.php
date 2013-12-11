<?php

namespace PHPixie\DB\Driver\Mongo\Parser;

class Group {
	
	protected $driver;
	protected $operator_parser;

	protected $logic_precedance = array(
		'and' => 2,
		'xor' => 1,
		'or'  => 0
	);
	
	public function __construct($driver, $operator_parser) {
		$this->driver = $driver;
		$this->operator_parser = $operator_parser;
	}
	
	protected function expand_group( &$group, $level = 0) {
		$left = $current = current($group);
		
		while (true) {
			if (($next = next($group)) === false)
				break;
				
			if ($this->logic_precedance[$next->logic] < $level) {
				prev($group);
				break;
			}
			
			$right = $this->expand_group($group, $this->logic_precedance[$next->logic] + 1);
			if ($right)
				$left = $this->merge($left, $right);
				
			$current = $next;
		}
		
		return $left;
	}
	
	protected function normalize_condition($condition, $convert_operator = false) {
		if ($condition instanceof \PHPixie\DB\Driver\Mongo\Condition\Expanded)
			return $condition;
		
		if ($condition instanceof \PHPixie\DB\Conditions\Condition\Operator) {
			if (!$convert_operator){
				return $condition;
			}else {
				$expanded = $this->driver->expanded_condition();
				$expanded->add($condition);
				$expanded->logic = $condition->logic;
				return $expanded;
			}
		}
		
		if ($condition instanceof \PHPixie\DB\Conditions\Condition\Group) {
			$group = $condition->conditions();
			$group = $this->expand_group($group);
			$group->logic = $condition->logic;
			if ($condition->negated())
				$group->negate();
				
			return $group;
		}
		
		
	}
	
	protected function merge($left, $right) {
		$left = $this->normalize_condition($left, true);
		$right = $this->normalize_condition($right);
		if ($left instanceof \PHPixie\DB\Conditions\Condition\Operator){
			$expanded = $this->driver->expanded_condition();
			$expanded->add($left);
			$expanded->logic = $left->logic;
			$left = $expanded;
		}
		
		if ($right->logic === 'and')
			return $left->add($right);
			
		if ($right->logic === 'or')
			return $left->add($right, 'or');
		
		if ($right->logic === 'xor') {
		
			$merged = $this->driver->expanded_condition();
			$right_clone = clone $right;
			$left_clone = clone $left;
			
			$merged->add($left);
			$merged->add($right_clone->negate());
			
			$right_part = $this->driver->expanded_condition();
			$right_part->add($left_clone->negate());
			$right_part->add($right);
			
			$merged->add($right_part, 'or');
			$merged->logic = $left->logic;
			return $merged;
		}
		
	}
	
	

	public function parse($group) {
		if (empty($group))
			return array();
			
		$expanded = $this->expand_group($group);
		$expanded = $this->normalize_condition($expanded, true);
		
		foreach($expanded->groups() as $group) {
			$and_group = array();
			foreach($group as $condition) {
				$condition = $this->operator_parser->parse($condition);
				foreach($condition as $field => $field_conditions) {
					$appended = false;
					foreach($and_group as $key=>$merged) {
						if (!isset($merged[$field])) {
							$and_group[$key][$field] = $field_conditions;
							$appended = true;
							break;
						}
					}
					if (!$appended)
						$and_group[] = array($field => $field_conditions);
				}
			}

			$count = count($and_group);
			if ($count === 1){
				$and_group = current($and_group);
			}elseif($count === 0) {
				continue;
			}else {
				$and_group = array('$and' => $and_group);
			}
			$and_groups[] = $and_group;
		}
		
		$count = count($and_groups);
		if ($count === 1) {
			$and_groups = current($and_groups);
		}else {
			$and_groups = array('$or' => $and_groups);
		}
		
		return $and_groups;
		
	}

}