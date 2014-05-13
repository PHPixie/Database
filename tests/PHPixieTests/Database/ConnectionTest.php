<?php

namespace PHPixieTests\Database;

/**
 * @coversDefaultClass \PHPixie\Database\Connection
 */
abstract class ConnectionTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $connection;
    protected $queryClass;
    protected $config;
    protected $driver;
    
    /**
     * @covers ::select
     * @covers ::update
     * @covers ::delete
     * @covers ::insert
     * @covers ::count
     */
    public function testDefaultQueries()
    {
        $this->queryTest(array('select','update','delete','insert','count'));
    }

    /**
     * @covers ::config
     */
    public function testConfig()
    {
        $this->assertEquals($this->config, $this->connection->config());
    }
    
    protected function queryTest($types)
    {
        foreach($types as $type) {
            $this->driver
                    ->expects($this->at(0))
                    ->method('query')
                    ->with($type, 'test')
                    ->will($this->returnValue('query'));
            $this->assertEquals('query', $this->connection->$type());
        }
    }
}
