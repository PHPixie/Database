<?php

namespace PHPixie\DB\Driver\Mongo\Condition;

class Expanded extends \PHPixie\DB\Conditions\Condition{
	protected $groups = array();
	
	public function __construct($condition = null) {
		if ($condition !== null)
			$this->add($condition);
	}
	
	protected function add_and($condition) {
		$new_groups = array();
		foreach($this->groups as $key=>$group) {
			if ($condition instanceof Expanded) {
				foreach($condition->groups as $new_group) 
					$new_groups[] = array_merge($group, $new_group);
			}else {
				$group[] = $condition;
				$new_groups[] = $group;
			}
		}
		$this->groups = $new_groups;
	}
	
	protected function add_or($group) {
		if ($group instanceof Expanded) {
			$this->groups = array_merge($this->groups, $group->groups);
		}else{
			$this->groups[] = array($group);
		}
		return $this;
	}
	
	public function add($condition, $logic = 'and') {
		if (empty($this->groups)) {
		
			if ($condition instanceof Expanded) {
				$this->groups = $condition->groups;
			}elseif($condition instanceof \PHPixie\DB\Conditions\Condition\Operator) {
				$this->groups[] = array($condition);
			}else {
				throw new \PHPixie\DB\Exception\Parser("You can only add Expanded and Operator conditions");
			}
			
		}elseif($logic == 'and') {
			$this->add_and($condition);
		}elseif($logic == 'or') {
			$this->add_or($condition);
		}else {
			throw new \PHPixie\DB\Exception\Parser("You can only use 'and' and 'or' logic");
		}
		
		return $this;
	}
	
	public function negate($debug=false) {
		$groups = array(array());
		$count = count($this->groups);
		$negated = array();
		
		for ($i = $count - 1; $i >= 0; $i--) {

			$group = $this->groups[$i];
			
			$merged = array();
			
			foreach($group as $operator) {
				if(!in_array($operator, $negated, true)){
					$operator->negate();
					$negated[] = $operator;
				}
				
				foreach($groups as $old_merged) {
					if (!in_array($operator, $old_merged, true)) {
						array_unshift($old_merged, $operator);
					}
					$merged[] = $old_merged;
					
				}
			}
			
			$groups = $this->optimize($merged);
		}
		$this->groups = $groups;
		return $this;
	}
	
	protected function optimize($groups) {
		$count = count($groups);
		$remove = array();
		for ($i = 0; $i < $count; $i++){
			for ($j = 0; $j < $count; $j++) {
			
				if ($i === $j)
					continue;
				
				if($this->is_subset($groups[$i], $groups[$j])){
					$remove[] = $j;
				}
				
			}
		}
		
		foreach($remove as $i)
			unset($groups[$i]);
		
		return array_values($groups);
	}
	
	protected function is_subset(&$subset, &$set) {
		foreach($subset as $item)
			if (!in_array($item, $set))
				return false;
				
		return true;
	}
	
	public function groups() {
		return $this->groups;
	}
	
	public function __clone(){
		foreach($this->groups as $key=>$group) {
			foreach($group as $item_key => $item) {
				$this->groups[$key][$item_key] = clone $item;
			}
		}
	}
	
}