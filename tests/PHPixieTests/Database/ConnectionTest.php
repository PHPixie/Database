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
