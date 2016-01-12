<?php
namespace PHPixie\Tests\Database\Driver\PDO;

class PDOConnectionTestStub extends \PHPixie\Database\Driver\PDO\Connection
{
    protected function buildPdo($connection, $user, $password, $options)
    {
        if (substr($connection, 0, 7) != 'sqlite:' || $user != 'test' || $password != 5 || $options !== array('someOption' => 5))
            throw new \Exception("Parameters don't match expected");

        return parent::buildPdo($connection, $user, $password, array());
    }

    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }
}

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Connection
 */
class ConnectionTest extends \PHPixie\Tests\Database\Type\SQL\ConnectionTest
{
    protected $databaseFile;
    protected $config;
    protected $queryClass = 'PHPixie\Database\Driver\PDO\Query';
    protected $pdoProperty;

    public function tearDown()
    {
        $this->pdoProperty->setValue($this->connection, null);
        unlink($this->databaseFile);
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
     * @covers ::execute
     */
    public function testExecute()
    {
        $this->connection->execute("INSERT INTO fairies(id,name) VALUES (1,'Tinkerbell')");
        $result = $this->connection->execute("Select * from fairies where id = ?", array(1));
        $this->assertEquals(array((object) array('id'=>1, 'name'=>'Tinkerbell')), $result->asArray());
    }
    
    /**
     * @covers ::disconnect
     */
    public function testDisconnect()
    {
        $this->connection->disconnect();
        $this->assertSame(null, $this->connection->pdo());
    }

    /**
     * @covers ::insertId
     */
    public function testInsertId()
    {
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
        $this->assertEquals('sqlite', $this->connection->adapterName());
    }

    /**
     * @covers ::execute
     */
    public function testException()
    {
        $this->setExpectedException('\Exception');
        $this->connection->execute('pixie');
    }
    
    /**
     * @covers ::beginTransaction
     * @covers ::commitTransaction
     * @covers ::rollbackTransaction
     * @covers ::<protected>
     */
    public function testTransaction()
    {
        $map = array(
            'beginTransaction' => 'beginTransaction',
            'commitTransaction' => 'commit',
            'rollbackTransaction' => 'rollBack',
        );
        $pdo = $this->quickMock('\stdClass', array_values($map));
        
        $this->connection->setPdo($pdo);
        
        foreach($map as $method => $pdoMethod) {
            $pdo
                ->expects($this->at(0))
                ->method($pdoMethod)
                ->with();
            $this->connection->$method();
        }
    }
    
    /**
     * @covers ::inTransaction
     * @covers ::<protected>
     */
    public function testInTransaction()
    {
        $pdo = $this->quickMock('\stdClass', array('intransaction'));
        $this->connection->setPdo($pdo);
        foreach(array(true, false) as $result) {
            $pdo
                ->expects($this->at(0))
                ->method('inTransaction')
                ->with()
                ->will($this->returnValue($result));
            $this->assertSame($result, $this->connection->inTransaction());
        }
    }
    
    /**
     * @covers ::rollbackTransactionTo
     * @covers ::<protected>
     */
    public function testRollbackTransactionTo()
    {
        $adapter = $this->prepareAdapter();
        $adapter
            ->expects($this->at(0))
            ->method('rollbackTransactionTo')
            ->with('test');
        $this->connection->rollbackTransactionTo('test');
    }
    
    protected function prepareSavepoint($name)
    {
        $adapter = $this->prepareAdapter();
        $adapter
            ->expects($this->at(0))
            ->method('createTransactionSavepoint')
            ->with($name);
    }
    
    protected function prepareConnect()
    {
        $this->databaseFile = tempnam(sys_get_temp_dir(), 'test.sqlite');
        $this->config = $this->getSliceData(array(
            'connection' => 'sqlite:'.$this->databaseFile,
            'user'       => 'test',
            'password'   =>  5,
            'driver'     => 'pdo',
            'connectionOptions' => array(
                'someOption' => 5
            ))
        );
        $this->database = $this->getMock('\PHPixie\Database', array('get'), array(null));
        $reflection = new \ReflectionClass("\PHPixie\Database\Driver\PDO\Connection");
        $this->pdoProperty = $reflection->getProperty('pdo');
        $this->pdoProperty->setAccessible(true);

        $this->driver = $this->getMock('\PHPixie\Database\Driver\PDO', array('query'), array($this->database));
        $this->connection = new PDOConnectionTestStub($this->driver, 'test', $this->config);
        $this->connection->execute("CREATE TABLE fairies(id INT PRIMARY_KEY,name VARCHAR(255))");
        $this->database
                ->expects($this->any())
                ->method('get')
                ->with ('test')
                ->will($this->returnValue($this->connection));
    }
    
    protected function prepareAdapter()
    {
        $adapter = $this->quickMock('\PHPixie\Database\Driver\PDO\Adapter\Sqlite', array());
        $this->connection->setAdapter($adapter);
        return $adapter;
    }

    /**
     * @covers ::__construct
     */
    public function testWrongOptionsException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception');
        $config = $this->getSliceData(array(
            'connection' => 'sqlite:'.$this->databaseFile,
            'user'   => 'pixie',
            'password' => 5,
            'connectionOptions' => 5
        ));
        $connection = new \PHPixie\Database\Driver\PDO\Connection($this->database->driver('pdo'), 'test', $config);
    }

}
