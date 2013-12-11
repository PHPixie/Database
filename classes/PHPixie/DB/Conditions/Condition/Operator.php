<?php

namespace PHPixie\DB\Conditions\Condition;

class Operator extends \PHPixie\DB\Conditions\Condition {
	
	public $field;
	public $operator;
	public $values;
	
	public function __construct($field, $operator, $values) {
		$this->field = $field;
		$this->operator = $operator;
		$this->values = $values;
	}
}