<?php

namespace PHPixieTests\Database\Driver\Mongo;

if(!class_exists('\MongoClient'))
    require_once(__DIR__.'/ConnectionTestFiles/MongoClient.php');

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Connection
 */
class ConnectionTest extends \PHPixieTests\Database\ConnectionTest
{
    protected $pixie;
    protected $queryClass = 'PHPixie\Database\Driver\Mongo\Query';
    
    public function setUp()
    {
        $this->database = $this->getMock('\PHPixie\Database', array('get'), array(null));
        $this->config = $this->sliceStub(array(
            'connection' => 'mongo://test:555/',
            'user'   => 'pixie',
            'password' => 5,
            'database' => 'test',
            'connectionOptions' => array(
                'connect'    =>  false
            )
        ));
        $this->driver = $this->getMock('\PHPixie\Database\Driver\Mongo', array('result', 'query'), array($this->database));
        $this->connection = new \PHPixie\Database\Driver\Mongo\Connection($this->driver, 'test', $this->config);
        $this->database
                        ->expects($this->any())
                        ->method('get')
                        ->with ('test')
                        ->will($this->returnValue($this->connection));
    }
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\Database\Connection::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }

    /**
     * @covers ::selectSingleQuery
     */
    public function testDefaultQueries()
    {
        $this->queryTest(array('selectSingle'));
    }
    
    /**
     * @covers ::run
     * @covers ::connect
     */
    public function testRunCursor()
    {
        $runner = $this->getMock('\PHPixie\Database\Driver\Mongo\Query\Runner');
        $cursor = $this->getMock('\MongoCursor', array(), array(), '', null, false);
        $runner
                ->expects($this->once())
                ->method('run')
                ->with($this->connection)
                ->will($this->returnValue($cursor));
        $this->driver
                ->expects($this->once())
                ->method('result')
                ->with ($cursor)
                ->will($this->returnValue(1));
        $this->assertEquals(1, $this->connection->run($runner));
    }

    /**
     * @covers ::run
     */
    public function testRunRaw()
    {
        $runner = $this->getMock('\PHPixie\Database\Driver\Mongo\Query\Runner');
        $runner
                ->expects($this->once())
                ->method('run')
                ->with($this->connection)
                ->will($this->returnValue(5));
        $this->assertEquals(5, $this->connection->run($runner));
    }

    /**
     * @covers ::setInsertId
     * @covers ::insertId
     */
    public function testSetGetInsertId()
    {
        $this->connection->setInsertId(5);
        $this->assertEquals(5, $this->connection->insertId());
    }

    /**
     * @covers ::client
     */
    public function testClient()
    {
        $this->assertAttributeEquals($this->connection->client(), 'client', $this->connection);
    }

    /**
     * @covers ::database
     */
    public function testDatabase()
    {
        $db = new \stdClass;
        $this->connection->client()->test = $db;
        $database = $this->connection->database();
        $this->assertEquals($db, $database);
        $this->assertEquals($db, $this->connection->database());
    }

    /**
     * @covers ::__construct
     */
    public function testWrongOptionsException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception');
        $config = $this->sliceStub(array(
            'connectionOptions' => 5,
            'user'   => 'pixie',
            'password' => 5,
            'database' => 'test',
            'connection' => 'mongodatabase.://test:555/',
        ));
        $connection = new \PHPixie\Database\Driver\Mongo\Connection($this->database->driver('pdo'), 'test', $config);
    }

}
