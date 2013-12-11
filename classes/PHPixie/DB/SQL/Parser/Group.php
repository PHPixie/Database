<?php

namespace PHPixie\DB\SQL\Parser;

abstract class Group {
	
	protected $db;
	protected $operator_parser;
	
	public function __construct($db, $operator_parser) {
		$this->db = $db;
		$this->operator_parser = $operator_parser;
	}
	
	public function parse($group) {
		$expr = $this->db->expr();
		$this->append_group($group, $expr);
		return $expr;
	}
	
	protected function append_condition($condition, $expr) {
		if ($condition->negated()) {
			$expr->sql .= 'NOT ';
		}
		
		if ($condition instanceof \PHPixie\DB\Conditions\Condition\Operator) {
			$expr->append($this->operator_parser->parse($condition));
			
		}elseif($condition instanceof \PHPixie\DB\Conditions\Condition\Group) {
			$expr->sql.= "( ";
			$this->append_group($condition->conditions(), $expr);
			$expr->sql.= " )";
			
		}else{
			throw new \PHPixie\DB\Exception\Parser("Unexpected condition type encountered");
		}
	}
	
	protected function append_group($group, $expr) {
		foreach($group as $key=>$condition) {
			if ($key > 0)
				$expr->sql.= ' '.strtoupper($condition->logic).' ';
			$this->append_condition($condition, $expr);
		}
	}
	
}