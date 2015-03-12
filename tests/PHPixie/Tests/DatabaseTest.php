<?php

namespace PHPixie\Tests;

/**
 * @coversDefaultClass \PHPixie\Database
 */
class DatabaseTest extends \PHPixie\Tests\AbstractDatabaseTest
{
    protected $config;
    protected $database;

    public function setUp()
    {
        $this->config = $this->getSliceData();
        $this->database = new \PHPixie\Database($this->config);
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::sql
     * @covers ::<protected>
     */
    public function testSql()
    {
        $sql = $this->database->sql();
        $this->assertInstanceOf('\PHPixie\Database\Type\SQL', $sql);
        $this->assertSame($sql, $this->database->sql());
    }
    
    /**
     * @covers ::sqlExpression
     * @covers ::<protected>
     */
    public function testSQLExpression()
    {
        $expr = $this->database->sqlExpression('');
        $this->assertInstanceOf('\PHPixie\Database\Type\SQL\Expression', $expr);
        $this->assertEquals('', $expr->sql);
        $this->assertEquals(array(), $expr->params);
        $expr = $this->database->sqlExpression('pixie', array('test'));
        $this->assertEquals('pixie', $expr->sql);
        $this->assertEquals(array('test'), $expr->params);
    }

    /**
     * @covers ::connectionDriverName
     * @covers ::<protected>
     */
    public function testConnectionDriverName()
    {
        foreach(array('pdo', 'PDO') as $key => $driverName) {
            $slice = $this->getSliceData(array(
                'driver' => $driverName,
            ));
            
            $this->config
                ->expects($this->at($key))
                ->method('slice')
                ->with ('default')
                ->will($this->returnValue($slice));
        }
        
        $this->assertSame('pdo', $this->database->connectionDriverName('default'));
        $this->setExpectedException('\PHPixie\Database\Exception\Driver');
        $this->database->connectionDriverName('default');
    }
    
    /**
     * @covers ::driver
     * @covers ::buildDriver
     */
    public function testDriver()
    {
        foreach (array('pdo', 'mongo') as $driverName) {
            $driver = $this->database->driver($driverName);
            $this->assertInstanceOf('\PHPixie\Database\Driver\\'.ucfirst($driverName), $driver);
            $this->assertAttributeEquals($this->database,'database',$driver);
            $driver2 = $this->database->driver($driverName);
            $this->assertEquals($driver, $driver2);
        }
    }

    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $slice = $this->getSliceData(array(
            'driver' => 'pdo',
        ));

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

        $database = $this->getMock('\PHPixie\Database', array('driver'), array($this->config));
        $driver = $this->getMock('\PHPixie\Database\Driver\PDO', array('buildConnection'), array($database));

        $conn1 = new \stdClass;
        $conn2 = new \stdClass;

        $driver
            ->expects($this->at(0))
            ->method('buildConnection')
            ->with('default', $slice)
            ->will($this->returnValue($conn1));

        $driver
            ->expects($this->at(1))
            ->method('buildConnection')
            ->with('test', $slice)
            ->will($this->returnValue($conn2));

        $database
            ->expects($this->exactly(2))
            ->method('driver')
            ->with ('pdo')
            ->will($this->returnValue($driver));

        $this->assertEquals($conn1, $database->get());
        $this->assertEquals($conn1, $database->get());
        $this->assertEquals($conn2, $database->get('test'));
        $this->assertEquals($conn2, $database->get('test'));

    }

    /**
     * @covers ::query
     */
    public function testQuery()
    {
        $database = $this->getMock('\PHPixie\Database', array('get'), array(null));
        $conn = $this->getMock('\PHPixie\Database\Driver\PDO', array(), array(), '', null, false);
        $database
            ->expects($this->at(0))
            ->method('get')
            ->with ('default')
            ->will($this->returnValue($conn));
        $database
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

        $this->assertEquals('query1', $database->query());
        $this->assertEquals('query2', $database->query('delete', 'test'));
    }

    /**
     * @covers ::values
     * @covers ::buildvalues
     */
    public function testValues()
    {
        $values = $this->database->values();
        $this->assertInstanceOf('\PHPixie\Database\Values', $values);
        $this->assertEquals($values, $this->database->values());
    }

}
