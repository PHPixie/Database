<?php

namespace PHPixie\DB\Driver\Mongo;

class Parser extends \PHPixie\DB\Parser{
	protected $group_parser;
	
	public function __construct($db, $driver, $config, $group_parser) {
		parent::__construct($db, $driver, $config);
		$this->group_parser = $group_parser;
	}
	
	public function parse($query) {
		$runner = $this->driver->runner();;
		switch($query->get_type()) {
			case 'select':
				return $this->select_query($query, $runner);
			case 'insert':
				return $this->insert_query($query, $runner);
			case 'update':
				return $this->update_query($query, $runner);
			case 'delete':
				return $this->delete_query($query, $runner);
			case 'count':
				return $this->count_query($query, $runner);
			default:
				throw new \Exception("Query type $type is not supported");
		}
	}
	
	public function select_query($query, $runner) {
		$this->chain_collection($query, $runner);
		$fields = $query->get_fields();
		$conditions = $this->group_parser->parse($query->get_conditions('where'));
		$limit = $query->get_limit();
		$offset = $query->get_offset();
		$order_by = $query->get_order_by();
		
		if (empty($offset) && $limit === 1 && empty($order_by)) {
			$runner->chain_method('findOne', array($conditions, $fields));
		}else {
			$runner->chain_method('find', array($conditions, $fields));
			
			if (!empty($order_by)) {
				$ordering =  array();
				foreach($order_by as $order) {
					list($column, $dir) = $order;
					$ordering[$column] = $dir == 'asc' ? 1 : -1;
				}
				$runner->chain_method('sort', array($ordering));
			}
			
			if ($limit !== null)
				$runner->chain_method('limit', array($limit));
				
			if ($offset !== null)
				$runner->chain_method('skip', array($offset));
		}
		
		return $runner;
	}
	
	public function insert_query($query, $runner) {
		$this->chain_collection($query, $runner);
		$data = $query->get_data();
		if ($data === null)
			throw new \PHPixie\DB\Exception\Parser("No data set for insertion");
			
		$runner->chain_method('insert', array($data));
		return $runner;
	}
	
	public function update_query($query, $runner) {
		$this->chain_collection($query, $runner);
		$data = $query->get_data();
		if ($data === null)
			throw new \PHPixie\DB\Exception\Parser("No data set for update");
			
		$conditions = $this->group_parser->parse($query->get_conditions('where'));
		$runner->chain_method('update', array($conditions, $data, array('multiple' => true)));
		return $runner;
	}
	
	public function delete_query($query, $runner) {
		$this->chain_collection($query, $runner);
		$conditions = $this->group_parser->parse($query->get_conditions('where'));
		$runner->chain_method('remove', array($conditions));
		return $runner;
	}
	
	public function count_query($query, $runner) {
		$this->chain_collection($query, $runner);
		$conditions = $this->group_parser->parse($query->get_conditions('where'));
		if (!empty($conditions))
			$runner->chain_method('find', array($conditions));
		$runner->chain_method('count');
		return $runner;
	}
	
	protected function chain_collection($query, $runner){
		if (($collection = $query->get_collection()) !== null) {
			$runner->chain_property($collection);
		}else
			throw new \PHPixie\DB\Exception\Parser("You must specify a collection for this query.");
	}
}