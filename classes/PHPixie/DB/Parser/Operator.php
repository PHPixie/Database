<?php

namespace PHPixie\DB\Parser;

abstract class Operator {
	protected $method_map = array();
	protected $operators;
	
	public function __construct() {
		$this->build_method_map();
	}
	
	public function parse($condition) {
		$operator = $condition->operator;
		if(!isset($this->method_map[$operator]))
			throw new \PHPixie\DB\Exception\Parser("The '{$operator}' operator is not supported");
		
		$method = $this->method_map[$operator];
		$field = $condition->field;
		$values = $condition->values;
		$negated = $condition->negated();
		
		return call_user_func(array($this, 'parse_'.$method), $field, $operator, $values, $negated);
	}
	
	protected function build_method_map() {
		foreach($this->operators as $method => $operators)
			foreach($operators as $operator)
				$this->method_map[$operator] = $method;
	}
}