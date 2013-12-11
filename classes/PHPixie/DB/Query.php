<?php

namespace PHPixie\DB;

abstract class Query {

	protected $db;
	protected $connection;
	protected $parser;
	protected $config;
	protected $type;
	
	protected $data = null;
	protected $fields = array(); 
	protected $limit;
	protected $offset;
	protected $order_by = array();
	protected $condition_builders = array();
	protected $last_used_builder;
	
	public function __construct($db, $connection, $parser, $config, $type) {
		$this->db         = $db;
		$this->connection = $connection;
		$this->parser     = $parser;
		$this->config     = $config;
		$this->type($type);
	}
	
	public function type($type){
		$this->type = $type;
		return $this;
	}
	
	public function get_type() {
		return $this->type;
	}
	
	public function data($data) {
		$this->data = $data;
		return $this;
	}
	
	public function get_data() {
		return $this->data;
	}
	
	public function fields($fields) {
		if ($fields !== null && !is_array($fields))
			throw new \PHPixie\DB\Exception\Builder("Field list must either be an array or NULL");
			
		$this->fields = $fields;
		return $this;
	}
	
	public function get_fields() {
		return $this->fields;
	}
	
	public function limit($limit) {
		if (!is_numeric($limit))
			throw new \PHPixie\DB\Exception\Builder("Limit must be a number");
			
		$this->limit = $limit;
		return $this;
	}
	
	public function get_limit() {
		return $this->limit;
	}
	
	public function offset($offset) {
		if (!is_numeric($offset))
			throw new \PHPixie\DB\Exception\Builder("Offset must be a number");
			
		$this->offset = $offset;
		return $this;
	}
	
	public function get_offset() {
		return $this->offset;
	}
	
	public function order_by($field, $dir = 'asc') {
		if ($dir !== 'asc' && $dir !== 'desc')
			throw new \PHPixie\DB\Exception\Builder("Order direction must be either 'asc' or  'desc'");
		
		$this->order_by[] = array($field, $dir);
		return $this;
	}
	
	public function get_order_by() {
		return $this->order_by;
	}
	
	protected function condition_builder($name) {
		if (!isset($this->condition_builders[$name]))
			$this->condition_builders[$name] = $this->db->condition_builder();
		 
		$this->last_used_builder = $this->condition_builders[$name];
		return $this->last_used_builder;
	}
	
	protected function last_used_builder() {
		if ($this->last_used_builder === null)
			throw new \PHPixie\DB\Exception\Builder("No builder ");
			
		return $this->last_used_builder;
	}
	
	public function get_conditions($name) {
		if (!isset($this->condition_builders[$name]))
			return array();
			
		return $this->condition_builders[$name]->get_conditions();
	}
	
	public function where() {
		$this->condition_builder('where')->add_condition('and', false, func_get_args());
		return $this;
	}
	
	public function or_where() {
		$this->condition_builder('where')->add_condition('or', false, func_get_args());
		return $this;
	}
	
	public function xor_where() {
		$this->condition_builder('where')->add_condition('xor', false, func_get_args());
		return $this;
	}
	
	public function where_not() {
		$this->condition_builder('where')->add_condition('and', true, func_get_args());
		return $this;
	}
	
	public function or_where_not() {
		$this->condition_builder('where')->add_condition('or', true, func_get_args());
		return $this;
	}
	
	public function xor_where_not() {
		$this->condition_builder('where')->add_condition('xor', true, func_get_args());
		return $this;
	}
	
	public function _and() {
		$this->last_used_builder()->add_condition('and', false, func_get_args());
		return $this;
	}
	
	public function _or() {
		$this->last_used_builder()->add_condition('or', false, func_get_args());
		return $this;
	}
	
	public function _xor() {
		$this->last_used_builder()->add_condition('xor', false, func_get_args());
		return $this;
	}
	
	public function _and_not() {
		$this->last_used_builder()->add_condition('and', true, func_get_args());
		return $this;
	}
	
	public function _or_not() {
		$this->last_used_builder()->add_condition('or', true, func_get_args());
		return $this;
	}
	
	public function _xor_not() {
		$this->last_used_builder()->add_condition('xor', true, func_get_args());
		return $this;
	}
	
	public function parse() {
		return $this->parser->parse($this);
	}
	
	public abstract function execute();

}