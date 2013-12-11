<?php

namespace PHPixie\DB;

abstract class Conditions {
	
	protected $pixie;
	protected $operator_map = array();
	
	public function __construct($pixie) {
		$this->pixie = $pixie;
		foreach($this->operators as $class => $operators) {
			if(!is_array($operators))
				$operators = array($operators);
			foreach($operators as $operator)
				$this->operator_map[$operator] = $class;
		}
	}
	
	public function builder() {
		return $this->pixie->db->condition_builder($this);
	}
	
	public function condition($field, $operator, $params = array()) {
		
		if (!array_key_exists($operator, $this->operator_map))
			throw new \Exception("No condition defined for operator $operator");
			
		$class = $this->operator_map[$operator];
		$condition = new $class($field, $operator);
		$condition->params($params);
		return $conditio;
	}
	
	protected abstract function operators();
	
}