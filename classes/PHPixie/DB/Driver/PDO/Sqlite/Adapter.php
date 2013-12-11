<?php

namespace PHPixie\DB\Driver\PDO\Sqlite;

class Adapter extends \PHPixie\DB\Driver\PDO\Adapter {

	public function list_columns($table) {
		return $this->connection->execute("PRAGMA table_info('$table')")->get_column('name');
	}
}
