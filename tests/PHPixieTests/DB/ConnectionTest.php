<?php

namespace PHPixieTests\DB;

/**
 * @coversDefaultClass \PHPixie\DB\Connection
 */
abstract class ConnectionTest extends PHPUnit_Framework_TestCase
{
    protected $connection;
    protected $queryClass;
    protected $config;
    protected $driver;
    
    protected abstract function setUp();
    
    /**
     * @covers ::query
     */
    public function testQuery()
    {
        $query = $this->connection->query();
        $this->assertEquals('select', $query->getType());
        $this->assertEquals($this->queryClass, get_class($query));
        $query = $this->connection->query('insert');
        $this->assertEquals('insert', $query->getType());
    }

    /**
     * @covers ::config
     */
    public function testConfig()
    {
        $this->assertEquals($this->config, $this->connection->config());
    }
}
