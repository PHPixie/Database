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
	
	protected function condition_builder($name = null) {
		if ($name === null) {
			if ($this->last_used_builder === null)
				throw new \PHPixie\DB\Exception\Builder("No builder ");
			return $this->last_used_builder;
		}
	
		if (!isset($this->condition_builders[$name]))
			$this->condition_builders[$name] = $this->db->conditions()->builder();
		 
		$this->last_used_builder = $this->condition_builders[$name];
		return $this->last_used_builder;
	}
	
	public function get_conditions($name) {
		if (!isset($this->condition_builders[$name]))
			return array();
			
		return $this->condition_builders[$name]->get_conditions();
	}
	
	public function add_condition($args, $logic = 'and', $negate = false, $builder_name = null) {
		$this->condition_builder($builder_name)->add_condition($logic, $negate, $args);
		return $this;
	}
	
	public function start_condition_group($logic = 'and', $builder_name = null) {
		$this->condition_builder($builder_name)->start_group($logic);
		return $this;
	}
	
	public function end_condition_group($builder_name = null) {
		$this->condition_builder($builder_name)->end_group();
		return $this;
	}
	
	public function where() {
		return $this->add_condition(func_get_args(), 'and', false, 'where');
	}
	
	public function or_where() {
		return $this->add_condition(func_get_args(), 'or', false, 'where');
	}
	
	public function xor_where() {
		return $this->add_condition(func_get_args(), 'xor', false, 'where');
	}
	
	public function where_not() {
		return $this->add_condition(func_get_args(), 'and', true, 'where');
	}
	
	public function or_where_not() {
		return $this->add_condition(func_get_args(), 'or', true, 'where');
	}
	
	public function xor_where_not() {
		return $this->add_condition(func_get_args(), 'xor', true, 'where');
	}
	
	public function start_where_group($logic = 'and') {
		return $this->start_condition_group($logic, 'where');
	}
	
	public function end_where_group() {
		return $this->end_condition_group('where');
	}
	
	public function _and() {
		return $this->add_condition(func_get_args(), 'and', false);
	}
	
	public function _or() {
		return $this->add_condition(func_get_args(), 'or', false);
	}
	
	public function _xor() {
		return $this->add_condition(func_get_args(), 'xor', false);
	}
	
	public function _and_not() {
		return $this->add_condition(func_get_args(), 'and', true);
	}
	
	public function _or_not() {
		return $this->add_condition(func_get_args(), 'or', true);
	}
	
	public function _xor_not() {
		return $this->add_condition(func_get_args(), 'xor', true);
	}
	
	public function start_group($logic='and') {
		return $this->start_condition_group($logic);
	}
	
	public function end_group() {
		return $this->end_condition_group();
	}
	
	public function parse() {
		return $this->parser->parse($this);
	}
	
	public abstract function execute();

}