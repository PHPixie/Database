<?php

abstract class ConnectionTest extends PHPUnit_Framework_TestCase {
	protected $connection;
	protected $query_class;
	protected $config;
	protected $driver;
	public function testQuery() {
		$query = $this->connection->query();
		$this->assertEquals('select', $query->get_type());
		$this->assertEquals($this->query_class, get_class($query));
		$query = $this->connection->query('insert');
		$this->assertEquals('insert', $query->get_type());
	}
	
	public function testConfig() {
		$this->assertEquals($this->config, $this->connection->config());
	}
	
	public function assertPixieException($callback) {
		$except = false;
		try {
			$callback();
		}catch (\PHPixie\Exception $e) {
			$except = true;
		}
		
		$this->assertEquals(true, $except);
	}
	
	protected function assertDBException($callback) {
		$except = false;
		try {
			$callback();
		}catch (\PHPixie\DB\Exception $e) {
			$except = true;
		}
		$this->assertEquals(true, $except);
	}
}