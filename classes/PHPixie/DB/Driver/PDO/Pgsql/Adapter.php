<?php

namespace PHPixie\DB\Driver\PDO\Pgsql;

class Adapter extends \PHPixie\DB\Driver\PDO\Adapter {

	public function __construct($config, $connection) {
		parent::__construct($config, $connection);
		$this->connection->execute("SET NAMES utf8");
	}
	
	public function insert_id() {
		try {
			return $this->connection->execute('SELECT lastval() as id')->get('id');
		}catch (\Exception $e){
			throw new \PHPixie\DB\Exception('Cannot get last insert id, probably no rows have been inserted yet.');
		}
	}

	public function list_columns($table) {
		return $this->connection
							->execute("select column_name from information_schema.columns where table_name = '{$table}' and table_catalog = current_database()")
							->get_column('column_name');
	}
}
