<?php

require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/ConnectionTest.php');

class MongoConnectionTestStub extends \PHPixie\DB\Driver\Mongo\Connection
{
    protected function connect($connection, $options)
    {
        if ($connection !== 'mongodb://test:555/' || $options !== array(
            'connect'  => false,
            'username' => 'pixie',
            'password' => 5
        ))
            throw new \Exception("Actual parameters differ from expected ones");

        return new \stdClass;
    }
}

class MongoConnectionTest extends ConnectionTest
{
    protected $pixie;
    protected $queryClass = 'PHPixie\DB\Driver\Mongo\Query';
    public function setUp()
    {
        $this->pixie = new \PHPixie\Pixie;
        $this->pixie-> db = $this->getMock('\PHPixie\DB', array('get'), array($this->pixie));
        $this->config = new \PHPixie\Config\Slice($this->pixie, 'test', array(
            'connection' => 'mongodb://test:555/',
            'user'   => 'pixie',
            'password' => 5,
            'connection_options' => array(
                'connect'    =>  false
            )
        ));
        $this->driver = $this->getMock('\PHPixie\DB\Driver\Mongo', array('result'), array($this->pixie->db));
        $this->connection = new MongoConnectionTestStub($this->driver, 'test', $this->config);
        $this->pixie-> db
                        ->expects($this->any())
                        ->method('get')
                        ->with ('test')
                        ->will($this->returnValue($this->connection));
    }

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

    public function testSetGetInsertId()
    {
        $this->connection->setInsertId(5);
        $this->assertEquals(5, $this->connection->insertId());
    }

    public function testClient()
    {
        $this->assertAttributeEquals($this->connection->client(), 'client', $this->connection);
    }

    public function testNoConnectionException()
    {
        $this->assertPixieException(function () {
            $config = new \PHPixie\Config\Slice($this->pixie, 'test', array());
            $connection = new PDOConnectionTestStub($this->pixie->db->driver('PDO'), 'test', $config);
        });
    }

    public function testWrongOptionsException()
    {
        $this->assertDBException(function () {
            $config = new \PHPixie\Config\Slice($this->pixie, 'test', array(
                'connection' => 'mongodb://test:555/',
                'user'   => 'pixie',
                'password' => 5,
                'connection_options' => 5
            ));
            $connection = new \PHPixie\DB\Driver\Mongo\Connection($this->pixie->db->driver('PDO'), 'test', $config);
        });
    }

}
