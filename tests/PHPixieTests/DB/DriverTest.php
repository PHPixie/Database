<?php
namespace PHPixieTests\DB;

/**
 * @coversDefaultClass \PHPixie\DB\Driver
 */
abstract class DriverTest extends \PHPixieTests\AbstractDBTest
{
    protected $driver;
    protected $connectionStub;
    protected $db;

    public function setUp()
    {
        $this->db = $this->getMock('\PHPixie\DB', array('get', 'parser'), array(null));
    }

    /**
     * @covers ::parser
     */
    public function testParser()
    {
        $parser = $this->driver->parser('test');
        $this->assertEquals($parser, $this->driver->parser('test'));
        $this->assertInstanceOf($this->parserClass, $parser);
    }

    /**
     * @covers ::query
     */
    public function testQuery()
    {
        $query = $this->driver->query('delete', 'test');
        $this->assertInstanceOf($this->queryClass, $query);
        $this->assertAttributeEquals($this->connectionStub, 'connection', $query);
        $this->assertAttributeEquals('config', 'config', $query);
        $this->assertAttributeInstanceOf($this->parserClass, 'parser', $query);
    }
    
}
