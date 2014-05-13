<?php
namespace PHPixieTests\Database;

/**
 * @coversDefaultClass \PHPixie\Database\Driver
 */
abstract class DriverTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $driver;
    protected $connectionStub;
    protected $database;
    protected $queryClass;
    protected $parserClass;
    protected $builderClass;

    public function setUp()
    {
        $this->database = $this->getMock('\PHPixie\Database', array('get', 'parser'), array(null));
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
        $this->assertAttributeInstanceOf($this->parserClass, 'parser', $query);
        $this->assertAttributeInstanceOf($this->builderClass, 'builder', $query);
    }
    
}
