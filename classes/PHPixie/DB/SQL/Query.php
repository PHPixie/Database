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

	public function union($query, $all=false) {
		$this->unions[] = array($query, $all);
		return $this;
	}
	
	public function get_unions() {
		return $this->unions;
	}
	
	protected function last_on_builder() {
		if (empty($this->joins))
			throw new \PHPixie\DB\Exception\Builder("Cannot add join conditions as no joins have been added to the query.");
		
		$join = end($this->joins);
		
		$this->last_used_builder = $join['builder'];
		return $this->last_used_builder;
	}
	
	public function add_on_condition($args, $logic = 'and', $negate = false) {
		$this->last_used_builder = $this->last_on_builder();
		$this->last_used_builder->add_condition($logic, $negate, $args);
		return $this;
	}
	
	public function having() {
		return $this->add_condition(func_get_args(), 'and', false, 'having');
	}
	
	public function or_having() {
		return $this->add_condition(func_get_args(), 'or', false, 'having');
	}
	
	public function xor_having() {
		return $this->add_condition(func_get_args(), 'xor', false, 'having');
	}
	
	public function having_not() {
		return $this->add_condition(func_get_args(), 'and', true, 'having');
	}
	
	public function or_having_not() {
		return $this->add_condition(func_get_args(), 'or', true, 'having');
	}
	
	public function xor_having_not() {
		return $this->add_condition(func_get_args(), 'xor', true, 'having');
	}
	
	public function start_having_group($logic = 'and') {
		return $this->start_condition_group($logic, 'having');
	}
	
	public function end_having_group() {
		return $this->end_condition_group('having');
	}
	
	public function on() {
		return $this->add_on_condition(func_get_args(), 'and', false);
	}
	
	public function or_on() {
		return $this->add_on_condition(func_get_args(), 'or', false);
	}
	
	public function xor_on() {
		return $this->add_on_condition(func_get_args(), 'xor', false);
	}
	
	public function on_not() {
		return $this->add_on_condition(func_get_args(), 'and', true);
	}
	
	public function or_on_not() {
		return $this->add_on_condition(func_get_args(), 'or', true);
	}
	
	public function xor_on_not() {
		return $this->add_on_condition(func_get_args(), 'xor', true);
	}
	
	public function start_on_group($logic = 'and') {
		$this->last_on_builder()->start_group($logic);
		return $this;
	}
	
	public function end_on_group() {
		$this->last_on_builder()->end_group();
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