<?php

namespace PHPixie\DB\Driver\PDO\Mysql;

class Adapter extends \PHPixie\DB\Driver\PDO\Adapter {
	
	public function __construct($config, $connection) {
		parent::__construct($config, $connection);
		$this->connection->execute("SET NAMES utf8");
	}
	
	public function list_columns($table) {
		return $this->connection->execute("DESCRIBE `$table`")->get_column('Field');
	}
}
