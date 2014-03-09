<?php
namespace PHPixieTests\DB\Driver\PDO;

class PDOConnectionTestStub extends \PHPixie\DB\Driver\PDO\Connection
{
    protected function connect($connection, $user, $password, $options)
    {
        if (substr($connection, 0, 7) != 'sqlite:' || $user != 'test' || $password != 5 || $options !== array('some_option' => 5))
            throw new \Exception("Parameters don't match expected");

        return parent::connect($connection, $user, $password, array());
    }

    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }
}

/**
 * @coversDefaultClass \PHPixie\DB\Driver\PDO\Adapter
 */
abstract class ConnectionTest extends \PHPixieTests\DB\ConnectionTest
{
    protected $dbFile;
    protected $config;
    protected $queryClass = 'PHPixie\DB\Driver\PDO\Query';
    protected $pdoProperty;

    public function setUp()
    {
        $this->dbFile = tempnam(sys_get_temp_dir(), 'test.sqlite');
        $this->config = $this->getSlice(array(
            'connection' => 'sqlite:'.$this->dbFile,
            'user'       => 'test',
            'password'   =>  5,
            'driver'     => 'PDO',
            'connectionOptions' => array(
                'someOption' => 5
            ))
        );
        $this->db = $this->getMock('\PHPixie\DB', array('get'), array(null));
        $reflection = new ReflectionClass("\PHPixie\DB\Driver\PDO\Connection");
        $this->pdoProperty = $reflection->getProperty('pdo');
        $this->pdoProperty->setAccessible(true);

        $this->connection = new PDOConnectionTestStub($this->db->driver('PDO'), 'test', $this->config);
        $this->connection->execute("CREATE TABLE fairies(id INT PRIMARY_KEY,name VARCHAR(255))");
        $this->db
                ->expects($this->any())
                ->method('get')
                ->with ('test')
                ->will($this->returnValue($this->connection));
    }

    public function tearDown()
    {
        $this->pdoProperty->setValue($this->connection, null);
        unlink($this->dbFile);
    }

    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $this->connection->execute("INSERT INTO fairies(id,name) VALUES (1,'Tinkerbell')");
        $result = $this->connection->execute("Select * from fairies where id = ?", array(1));
        $this->assertEquals(array((object) array('id'=>1, 'name'=>'Tinkerbell')),$result->asArray());
    }
    
    /**
     * @covers ::insertId
     */
    public function testInsertId()
    {
        $this->assertDBException(function () {
            $this->connection->insertId();
        });
        $this->connection->execute("INSERT INTO fairies(id,name) VALUES (1,'Tinkerbell')");
        $this->assertEquals(1, $this->connection->insertId());
    }

    /**
     * @covers ::listColumns
     */
    public function testListColumns()
    {
        $this->assertEquals(array('id', 'name'), $this->connection->listColumns('fairies'));
    }

    /**
     * @covers ::pdo
     */
    public function testPdo()
    {
        $this->assertInstanceOf('\PDO', $this->connection->pdo());
    }

    /**
     * @covers ::adapterName
     */
    public function testAdapterName()
    {
        $this->assertEquals('Sqlite', $this->connection->adapterName());
    }

    public function testNoConnectionException()
    {
        $this->setExpectedException('\PHPixie\DB\Exception');
        $connection = new PDOConnectionTestStub($this->db->driver('PDO'), 'test', $this->sliceStub());
    }

    /**
     * @covers ::execute
     */
    public function testException()
    {
        $this->connection->execute('pixie');
    }

    public function testWrongOptionsException()
    {
        $this->setExpectedException('\PHPixie\DB\Exception');
        $config = $this->sliceStub(array(
            'connection' => 'sqlite:'.$this->dbFile,
            'user'   => 'pixie',
            'password' => 5,
            'connection_options' => 5
        ));
        $connection = new \PHPixie\DB\Driver\Mongo\Connection($this->db->driver('PDO'), 'test', $config);
    }

}
