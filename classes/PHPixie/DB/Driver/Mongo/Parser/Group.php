<?php

namespace PHPixie\DB\Driver\Mongo\Parser;

class Group extends \PHPixie\DB\Conditions\Logic\Parser{
	
	protected $driver;
	protected $operator_parser;

	
	public function __construct($driver, $operator_parser) {
		$this->driver = $driver;
		$this->operator_parser = $operator_parser;
	}
	
	protected function normalize($condition, $convert_operator = true) {
	
		if ($condition instanceof \PHPixie\DB\Conditions\Condition\Group) {
			$group = $condition->conditions();
			$group = $this->expand_group($group);
			$group->logic = $condition->logic;
			if ($condition->negated())
				$group->negate();
				
			return $group;
		}
		
		if ($condition instanceof \PHPixie\DB\Conditions\Condition\Operator && $convert_operator) {
			$expanded = $this->driver->expanded_condition();
			$expanded->add($condition);
			$expanded->logic = $condition->logic;
			return $expanded;
		}
		
		return $condition;
		
	}
	
	protected function merge($left, $right) {
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
		$expanded = $this->normalize($expanded);
		
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