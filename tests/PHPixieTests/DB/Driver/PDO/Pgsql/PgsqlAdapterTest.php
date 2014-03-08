<?php

require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/Driver/PDO/PDOAdapterTest.php');

class PgsqlAdapterTest extends PDOAdapterTest
{
    protected $listColumnsQuery = "select column_name from information_schema.columns where table_name = 'fairies' and table_catalog = current_database()";

    protected $listColumnsColumn = 'column_name';

    public function setUp()
    {
        parent::setUp();
        $this->connection
                        ->expects($this->at(0))
                        ->method('execute')
                        ->with('SET NAMES utf8')
                        ->will($this->returnValue(null));
        $this->adapter = new \PHPixie\DB\Driver\PDO\Pgsql\Adapter('test', $this->connection);
    }

    public function testInsertId()
    {
        $this->prepareQueryColumnAssertion('SELECT lastval() as id', 'get', 'id', 1);
        $this->assertEquals(1, $this->adapter->insertId());
    }

    public function testInsertIdNull()
    {
        $this->connection
                    ->expects($this->once())
                    ->method('execute')
                    ->with('SELECT lastval() as id')
                    ->will($this->returnCallback(function () {
                        throw new \Exception('test');
                    }));

        $this->assertException(function () {
            $this->adapter->insertId();
        });
    }

}
