<?php

namespace PHPixieTests\DB;

/**
 * @coversDefaultClass \PHPixie\DB\Connection
 */
abstract class ConnectionTest extends \PHPUnit_Framework_TestCase
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
    
    protected function sliceStub($data = array()) {
        $slice = $this->getMock('\PHPixie\Config\Slice', array('slice', 'get'), array(), '', false);
        foreach($data as $key => $value)
            $slice
                ->expects($this->any())
                ->method('get')
                ->with ($key)
                ->will($this->returnValue($value));
        return $slice;
    }
}
