<?php

namespace PHPixie\DB\SQL;

abstract class Query extends \PHPixie\DB\Query{
	
	protected $table;
	protected $group_by = array();
	protected $joins = array();
	protected $unions = array();
	
	public function table($table, $alias = null) {
		$this->table = array(
			'table' => $table,
			'alias' => $alias
		);
		
		return $this;
	}
	
	public function get_table() {
		return $this->table;
	}
	
	public function group_by($field = null) {
		$this->group_by[] = $field;
		return $this;
	}
	
	public function get_group_by() {
		return $this->group_by;
	}
	
	public function join($table, $alias = null, $type = 'inner') {
		$this->joins[] = array(
			'builder' => $this->db->condition_builder('=*'),
			'table' => $table,
			'alias' => $alias,
			'type'  => $type
		);
		return $this;
	}
	
	public function get_joins() {
		return $this->joins;
	}

	protected function last_on_builder() {
		if (empty($this->joins))
			throw new \PHPixie\DB\Exception\Builder("Cannot add join conditions as no joins have been added to the query.");
		
		$join = end($this->joins);
		
		$this->last_used_builder = $join['builder'];
		return $this->last_used_builder;
	}
	
	public function union($query, $all=false) {
		$this->unions[] = array($query, $all);
		return $this;
	}
	
	public function get_unions() {
		return $this->unions;
	}
	
	public function having() {
		$this->condition_builder('having')->add_condition('and', false, func_get_args());
		return $this;
	}
	
	public function or_having() {
		$this->condition_builder('having')->add_condition('or', false, func_get_args());
		return $this;
	}
	
	public function xor_having() {
		$this->condition_builder('having')->add_condition('xor', false, func_get_args());
		return $this;
	}
	
	public function having_not() {
		$this->condition_builder('having')->add_condition('and', true, func_get_args());
		return $this;
	}
	
	public function or_having_not() {
		$this->condition_builder('having')->add_condition('or', true, func_get_args());
		return $this;
	}
	
	public function xor_having_not() {
		$this->condition_builder('having')->add_condition('xor', true, func_get_args());
		return $this;
	}
	
		public function on() {
		$this->last_on_builder()->add_condition('and', false, func_get_args());
		return $this;
	}
	
	public function or_on() {
		$this->last_on_builder()->add_condition('or', false, func_get_args());
		return $this;
	}
	
	public function xor_on() {
		$this->last_on_builder()->add_condition('xor', false, func_get_args());
		return $this;
	}
	
	public function on_not() {
		$this->last_on_builder()->add_condition('and', true, func_get_args());
		return $this;
	}
	
	public function or_on_not() {
		$this->last_on_builder()->add_condition('or', true, func_get_args());
		return $this;
	}
	
	public function xor_on_not() {
		$this->last_on_builder()->add_condition('xor', true, func_get_args());
		return $this;
	}
	
	public function execute() {
		$expr = $this->parse();
		$result = $this->connection->execute($expr->sql, $expr->params);
		if ($this->get_type() === 'count')
			return $result->get('count');
		return $result;
	}
}