<?php
namespace PHPixie\Tests\Database;

/**
 * @coversDefaultClass \PHPixie\Database\Driver
 */
abstract class DriverTest extends \PHPixie\Tests\AbstractDatabaseTest
{
    protected $driver;
    protected $connectionStub;
    protected $database;
    protected $conditionsClass;
    protected $queryClass;
    protected $parserClass;
    protected $builderClass;

    public function setUp()
    {
        $this->database = $this->getMock('\PHPixie\Database', array('get', 'parser'), array(null));
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        
    }

    /**
     * @covers ::conditions
     * @covers ::buildConditions
     */
    public function testConditions()
    {
        $conditions = $this->driver->conditions();
        $this->assertSame($conditions, $this->driver->conditions());
        $this->assertInstanceOf($this->conditionsClass, $conditions);
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
    
    abstract public function testQueryBuilder();
}
