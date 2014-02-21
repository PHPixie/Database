<?php

namespace PHPixie\DB;

class Conditions {
	
	public function placeholder() {
		return new Conditions\Condition\Placeholder();
	}
	
	public function operator($field, $operator, $values) {
		return new Condition\Placeholder($field, $operator, $values);
	}
	
	public function group() {
		return new Conditions\Condition\Group();
	}
	
	public function builder($default_operator = '=') {
		return new Conditions\Builder($this, $default_operator);
	}
}