<?php

namespace PHPixie\Tests\Database;

/**
 * @coversDefaultClass \PHPixie\Database\Connection
 */
abstract class ConnectionTest extends \PHPixie\Tests\AbstractDatabaseTest
{
    protected $connection;
    protected $queryClass;
    protected $config;
    protected $driver;

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::selectQuery
     * @covers ::updateQuery
     * @covers ::deleteQuery
     * @covers ::insertQuery
     * @covers ::countQuery
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
            $method = $type.'Query';
            $this->assertEquals('query', $this->connection->$method());
        }
    }
}
