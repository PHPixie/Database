<?php

require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/Driver/PDO/PDOAdapterTest.php');

class MysqlAdapterTest extends PDOAdapterTest {
	protected $list_columns_query = 'DESCRIBE `fairies`';
	protected $list_columns_column = 'Field';
	public function setUp(){
		parent::setUp();
		$this->connection
						->expects($this->at(0))
						->method('execute')
						->with('SET NAMES utf8')
						->will($this->returnValue(null));
		$this->adapter = new \PHPixie\DB\Driver\PDO\Mysql\Adapter('test', $this->connection);
	}
}