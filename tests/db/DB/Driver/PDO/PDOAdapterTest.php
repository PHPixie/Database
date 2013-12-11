<?php

abstract class PDOAdapterTest extends PHPUnit_Framework_TestCase {
	protected $adapter;
	protected $connection;
	protected $result;
	protected $pdo_stub;
	
	protected $list_columns_query;
	protected $list_columns_column;
	protected $connection_value_map = array();
	
	public function setUp() {
		$this->connection = $this->getMock('\PHPixie\DB\Driver\PDO\Connection', array('execute', 'pdo'), array(), '', false);
		$this->result = $this->getMock('\PHPixie\DB\Driver\PDO\Result', array('get_column', 'get'), array(), '', false);
		$this->pdo_stub = $this->getMock('Stub', array('lastInsertId'), array(), '', false );
	}
	
	public function testListColumn() {
		
		$this->prepareQueryColumnAssertion($this->list_columns_query, 'get_column', $this->list_columns_column, array('id', 'name'));
		$this->adapter->list_columns('fairies');
	}
	
	public function testInsertId() {
		$this->connection
						->expects($this->any())
						->method('pdo')
						->with()
						->will($this->returnValue($this->pdo_stub));
						
		$this->pdo_stub
						->expects($this->any())
						->method('lastInsertId')
						->with ()
						->will($this->returnValue(1));
						
		$this->assertEquals(1, $this->adapter->insert_id());
	}
	
	public function testInsertIdNull() {
		$this->connection
						->expects($this->any())
						->method('pdo')
						->with()
						->will($this->returnValue($this->pdo_stub));
						
		$this->pdo_stub
						->expects($this->any())
						->method('lastInsertId')
						->with ()
						->will($this->returnValue(0));
						
		$this->assertException(function() { 
			print_r($this->adapter->insert_id()); die;
		});
	}
	
	protected function assertException($callback) {
		$except = false;
		try {
			$callback();
		}catch (\PHPixie\DB\Exception $e) {
			$except = true;
		}
		$this->assertEquals(true, $except);
	}
	
	protected function prepareQueryColumnAssertion($query, $method,  $column, $result) {
		$this->connection
					->expects($this->at(0))
					->method('execute')
					->with($query)
					->will($this->returnValue($this->result));
					
		$this->result
					->expects($this->once())
					->method($method)
					->with($column)
					->will($this->returnValue($result));
	}
	
}