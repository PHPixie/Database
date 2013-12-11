<?php

namespace PHPixie\DB\SQL;

class Expression {
	
	public $sql;
	public $params;
	
	public function __construct($sql = '', $params = array()) {
		$this->sql = $sql;
		$this->params = $params;
	}
	
	public function append($expr) {
		$this->sql.= $expr->sql;
		$this->params = array_merge($this->params, $expr->params);
		return $this;
	}
}