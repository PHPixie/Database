<?php

namespace PHPixie\DB\Driver\Mongo;

class Query extends \PHPixie\DB\Query{
	
	protected $collection;
	
	public function collection($collection) {
		$this->collection = $collection;
		return $this;
	}
	
	public function get_collection() {
		return $this->collection;
	}
	
	public function parse() {
		return $this->parser->parse($this);
	}
	
	public function execute() {
		return $this->connection->run($this->parse());
	}
	
}