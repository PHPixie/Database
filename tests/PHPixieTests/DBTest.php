<?php

namespace PHPixieTests;

/**
 * @coversDefaultClass \PHPixie\DB
 */
class DBTest extends PHPUnit_Framework_TestCase
{
    protected $config;
    protected $db;

    public function setUp()
    {
        $this->config = $this->sliceStub();
        $this->db = new PHPixie\DB($this->config);
    }

    /**
     * @covers ::expr
     */
    public function testExpr()
    {
        $expr = $this->db->expr();
        $this->assertInstanceOf('PHPixie\DB\SQL\Expression', $expr);
        $this->assertEquals('', $expr->sql);
        $this->assertEquals(array(), $expr->params);
        $expr = $this->db->expr('pixie');
        $this->assertEquals('pixie', $expr->sql);
        $this->assertEquals(array(), $expr->params);
        $expr = $this->db->expr('pixie', array('test'));
        $this->assertEquals('pixie', $expr->sql);
        $this->assertEquals(array('test'), $expr->params);
    }

    /**
     * @covers ::driver
     * @covers ::buildDriver
     */
    public function testDriver()
    {
        foreach (array('PDO', 'Mongo') as $driverName) {
            $driver = $this->db->driver($driverName);
            $this->assertInstanceOf('PHPixie\DB\Driver\\'.$driverName, $driver);
            $this->assertAttributeEquals($this->db,'db',$driver);
            $driver2 = $this->db->driver($driverName);
            $this->assertEquals($driver, $driver2);
        }
    }

    /**
     * @covers ::get
     * @covers ::getConfig
     */
    public function testGet()
    {
        $slice = $this->sliceStub();
        $slice
                ->expects($this->any())
                ->method('get')
                ->with ('driver')
                ->will($this->returnValue('PDO'));

        $this->config
                    ->expects($this->at(0))
                    ->method('slice')
                    ->with ('default')
                    ->will($this->returnValue($slice));
        
        $this->config
                    ->expects($this->at(1))
                    ->method('slice')
                    ->with ('test')
                    ->will($this->returnValue($slice));
        
        $db = $this->getMock('\PHPixie\DB', array('driver'), array(null));
        $driver = $this->getMock('\PHPixie\DB\Driver\PDO', array('buildConnection'), array($db));

        $conn1 = new \stdClass;
        $conn2 = new \stdClass;

        $driver
            ->expects($this->at(0))
            ->method('buildConnection')
            ->with('default', $slice1)
            ->will($this->returnValue($conn1));

        $driver
            ->expects($this->at(1))
            ->method('buildConnection')
            ->with('test', $slice2)
            ->will($this->returnValue($conn2));

        $db
            ->expects($this->exactly(2))
            ->method('driver')
            ->with ('PDO')
            ->will($this->returnValue($driver));

        $this->assertEquals($conn1, $db->get());
        $this->assertEquals($conn1, $db->get());
        $this->assertEquals($conn2, $db->get('test'));
        $this->assertEquals($conn2, $db->get('test'));

    }

    /**
     * @covers ::query
     */
    public function testQuery()
    {
        $db = $this->getMock('\PHPixie\DB', array('get'), array(null));
        $conn = $this->getMock('\PHPixie\DB\Driver\PDO', array(), array(), '', null, false);
        $db
            ->expects($this->at(0))
            ->method('get')
            ->with ('default')
            ->will($this->returnValue($conn));
        $db
            ->expects($this->at(1))
            ->method('get')
            ->with ('test')
            ->will($this->returnValue($conn));

        $conn
            ->expects($this->at(0))
            ->method('query')
            ->with('select')
            ->will($this->returnValue('query1'));

        $conn
            ->expects($this->at(1))
            ->method('query')
            ->with('delete')
            ->will($this->returnValue('query2'));

        $this->assertEquals('query1', $db->query());
        $this->assertEquals('query2', $db->query('delete', 'test'));
    }
    
    /**
     * @covers ::conditions
     */
    public function testConditions()
    {
        $conditions = $this->db->conditions();
        $this->assertInstanceOf('PHPixie\DB\Conditions', $conditions);
        $this->assertEquals($conditions, $this->db->conditions());
    }
    
    protected function sliceStub() {
        return $this->getMock('\PHPixie\Config\Slice', array('slice', 'get'), array(), '', false);
    }
}
