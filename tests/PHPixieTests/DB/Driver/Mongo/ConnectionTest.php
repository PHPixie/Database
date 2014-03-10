<?php
namespace PHPixieTests\DB\Driver\Mongo;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\Mongo\Connection
 */
class ConnectionTest extends \PHPixieTests\DB\ConnectionTest
{
    protected $pixie;
    protected $queryClass = 'PHPixie\DB\Driver\Mongo\Query';
    public function setUp()
    {
        $this->db = $this->getMock('\PHPixie\DB', array('get'), array(null));
        $this->config = $this->sliceStub(array(
            'connection' => 'mongodb://test:555/',
            'user'   => 'pixie',
            'password' => 5,
            'connectionOptions' => array(
                'connect'    =>  false
            )
        ));
        $this->driver = $this->getMock('\PHPixie\DB\Driver\Mongo', array('result'), array($this->db));
        $this->connection = new \PHPixie\DB\Driver\Mongo\Connection($this->driver, 'test', $this->config);
        $this-> db
                        ->expects($this->any())
                        ->method('get')
                        ->with ('test')
                        ->will($this->returnValue($this->connection));
    }

    /**
     * @covers ::run
     * @covers ::connect
     */
    public function testRunCursor()
    {
        $runner = $this->getMock('\PHPixie\DB\Driver\Mongo\Query\Runner');
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
        $runner = $this->getMock('\PHPixie\DB\Driver\Mongo\Query\Runner');
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
     * @covers ::__construct
     */
    public function testWrongOptionsException()
    {
        $this->setExpectedException('\PHPixie\DB\Exception');
        $config = $this->sliceStub(array(
            'connectionOptions' => 5,
            'user'   => 'pixie',
            'password' => 5,
            'connection' => 'mongodb://test:555/',
        ));
        $connection = new \PHPixie\DB\Driver\Mongo\Connection($this->db->driver('PDO'), 'test', $config);
    }

}
