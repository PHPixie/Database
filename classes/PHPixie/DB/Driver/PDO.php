<?php

namespace PHPixie\DB\Driver;

class PDO extends \PHPixie\DB\Driver{
	
	public function build_connection($connection_name, $config) {
		return new \PHPixie\DB\Driver\PDO\Connection($this, $connection_name, $config);
	}
	
	public function build_parser_instance($connection_name) {
		$connection      = $this->db->get($connection_name);
		$adapter_name    = $connection->adapter_name();
		$config          = $connection->config();
		$fragment_parser = $this->fragment_parser($adapter_name);
		$operator_parser = $this->operator_parser($adapter_name, $fragment_parser);
		$group_parser    = $this->group_parser($adapter_name, $operator_parser);
		return $this->build_parser($adapter_name, $config, $fragment_parser, $group_parser);
	}
	
	public function adapter($name, $config, $connection) {
		$class = '\PHPixie\DB\Driver\PDO\\'.$name.'\Adapter';
		return new $class($config, $connection);
	}
	
	public function build_parser($adapter_name, $config, $fragment_parser, $group_parser) {
		$class = '\PHPixie\DB\Driver\PDO\\'.$adapter_name.'\Parser';
		return new $class($this->db, $this, $config, $fragment_parser, $group_parser);
	}
	
	public function fragment_parser($adapter_name) {
		$class = '\PHPixie\DB\Driver\PDO\\'.$adapter_name.'\Parser\Fragment';
		return new $class;
	}
	
	public function operator_parser($adapter_name, $fragment_parser) {
		$class = '\PHPixie\DB\Driver\PDO\\'.$adapter_name.'\Parser\Operator';
		return new $class($this->db, $fragment_parser);
	}
	
	public function group_parser($adapter_name, $operator_parser) {
		$class = '\PHPixie\DB\Driver\PDO\\'.$adapter_name.'\Parser\Group';
		return new $class($this->db, $operator_parser);
	}
	
	public function build_query($connection, $parser, $config, $type) {
		return new \PHPixie\DB\Driver\PDO\Query($this->db, $connection, $parser, $config, $type);
	}
	
	public function result($statement) {
		return new \PHPixie\DB\Driver\PDO\Result($statement);
	}
	
}