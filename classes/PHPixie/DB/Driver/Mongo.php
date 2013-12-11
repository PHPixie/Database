<?php

namespace PHPixie\DB\Driver;

class Mongo extends \PHPixie\DB\Driver{
	

	public function build_connection($connection_name, $config) {
		return new \PHPixie\DB\Driver\Mongo\Connection($this, $connection_name, $config);
	}
	
	public function build_parser_instance($connection_name) {
		$connection      = $this->db->get($connection_name);
		$config          = $connection->config();
		$operator_parser = $this->operator_parser();
		$group_parser    = $this->group_parser($operator_parser);
		return $this->build_parser($config, $group_parser);
	}
	
	public function build_parser($config, $group_parser) {
		return new \PHPixie\DB\Driver\Mongo\Parser($this->db, $this, $config, $group_parser);
	}
	
	public function operator_parser() {
		return new \PHPixie\DB\Driver\Mongo\Parser\Operator;
	}
	
	public function group_parser($operator_parser) {
		return new \PHPixie\DB\Driver\Mongo\Parser\Group($this, $operator_parser);
	}
	
	public function build_query($connection, $parser, $config, $type) {
		return new \PHPixie\DB\Driver\Mongo\Query($this->db, $connection, $parser, $config, $type);
	}
	
	public function result($cursor) {
		return new \PHPixie\DB\Driver\Mongo\Result($cursor);
	}
	
	public function expanded_condition($condition = null) {
		return new \PHPixie\DB\Driver\Mongo\Condition\Expanded($condition);
	}
	
	public function runner(){
		return new \PHPixie\DB\Driver\Mongo\Query\Runner;
	}
	
}