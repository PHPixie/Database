<?php

require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/Driver/PDO/PDOAdapterTest.php');

class SqliteAdapterTest extends PDOAdapterTest {
	protected $list_columns_query = "PRAGMA table_info('fairies')";
	protected $list_columns_column = 'name';
	public function setUp(){
		parent::setUp();
		$this->adapter = new \PHPixie\DB\Driver\PDO\Sqlite\Adapter('test', $this->connection);
	}
}