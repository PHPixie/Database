<?php

namespace PHPixie\DB\Query\Condition;

abstract class Operator extends PHPixie\DB\Query\Condition {
	
	protected $field;
	protected $operator;
	protected $value;
	
	public function __construct(){
		$this->field = $field;
	}
	
	public function inverse
}