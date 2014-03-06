<?php

abstract class PDOAdapterTest extends PHPUnit_Framework_TestCase
{
    protected $adapter;
    protected $connection;
    protected $result;
    protected $pdoStub;

    protected $listColumnsQuery;
    protected $listColumnsColumn;
    protected $connectionValueMap = array();

    public function setUp()
    {
        $this->connection = $this->getMock('\PHPixie\DB\Driver\PDO\Connection', array('execute', 'pdo'), array(), '', false);
        $this->result = $this->getMock('\PHPixie\DB\Driver\PDO\Result', array('get_column', 'get'), array(), '', false);
        $this->pdoStub = $this->getMock('Stub', array('lastInsertId'), array(), '', false );
    }

    public function testListColumn()
    {
        $this->prepareQueryColumnAssertion($this->listColumnsQuery, 'get_column', $this->listColumnsColumn, array('id', 'name'));
        $this->adapter->listColumns('fairies');
    }

    public function testInsertId()
    {
        $this->connection
                        ->expects($this->any())
                        ->method('pdo')
                        ->with()
                        ->will($this->returnValue($this->pdoStub));

        $this->pdoStub
                        ->expects($this->any())
                        ->method('lastInsertId')
                        ->with ()
                        ->will($this->returnValue(1));

        $this->assertEquals(1, $this->adapter->insertId());
    }

    public function testInsertIdNull()
    {
        $this->connection
                        ->expects($this->any())
                        ->method('pdo')
                        ->with()
                        ->will($this->returnValue($this->pdoStub));

        $this->pdoStub
                        ->expects($this->any())
                        ->method('lastInsertId')
                        ->with ()
                        ->will($this->returnValue(0));

        $this->assertException(function () {
            print_r($this->adapter->insertId()); die;
        });
    }

    protected function assertException($callback)
    {
        $except = false;
        try {
            $callback();
        } catch (\PHPixie\DB\Exception $e) {
            $except = true;
        }
        $this->assertEquals(true, $except);
    }

    protected function prepareQueryColumnAssertion($query, $method,  $column, $result)
    {
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
