<?php

require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/ConnectionTest.php');

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

class PDOConnectionTest extends ConnectionTest
{
    protected $pixie;
    protected $dbFile;
    protected $queryClass = 'PHPixie\DB\Driver\PDO\Query';
    protected $pdoProperty;

    public function setUp()
    {
        $this->pixie = new \PHPixie\Pixie;
        $this->dbFile = tempnam(sys_get_temp_dir(), 'test.sqlite');
        $this->config = new \PHPixie\Config\Slice($this->pixie, 'test', array(
            'connection' => 'sqlite:'.$this->dbFile,
            'user'       => 'test',
            'password'   =>  5,
            'driver'     => 'PDO',
            'connection_options' => array(
                'some_option' => 5
            )
        ));
        $this->pixie-> db = $this->getMock('\PHPixie\DB', array('get'), array($this->pixie));
        $reflection = new ReflectionClass("\PHPixie\DB\Driver\PDO\Connection");
        $this->pdoProperty = $reflection->getProperty('pdo');
        $this->pdoProperty->setAccessible(true);

        $this->connection = new PDOConnectionTestStub($this->pixie->db->driver('PDO'), 'test', $this->config);
        $this->connection->execute("CREATE TABLE fairies(id INT PRIMARY_KEY,name VARCHAR(255))");
        $this->pixie-> db
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

    public function testExecute()
    {
        $this->connection->execute("INSERT INTO fairies(id,name) VALUES (1,'Tinkerbell')");
        $result = $this->connection->execute("Select * from fairies where id = ?", array(1));
        $this->assertEquals(array((object) array('id'=>1, 'name'=>'Tinkerbell')),$result->asArray());
    }
    public function testInsertId()
    {
        $this->assertDBException(function () {
            $this->connection->insertId();
        });
        $this->connection->execute("INSERT INTO fairies(id,name) VALUES (1,'Tinkerbell')");
        $this->assertEquals(1, $this->connection->insertId());
    }

    public function testListColumns()
    {
        $this->assertEquals(array('id', 'name'), $this->connection->listColumns('fairies'));
    }

    public function testPdo()
    {
        $this->assertEquals('PDO', get_class($this->connection->pdo()));
    }

    public function testAdapterName()
    {
        $this->assertEquals('Sqlite', $this->connection->adapterName());
    }

    public function testNoConnectionException()
    {
        $this->assertPixieException(function () {
            $config = new \PHPixie\Config\Slice($this->pixie, 'test', array());
            $connection = new PDOConnectionTestStub($this->pixie->db->driver('PDO'), 'test', $config);
        });
    }

    public function testException()
    {
        $this->assertException(function () {
            $this->connection->execute('pixie');
        });
    }

    protected function assertException($callback)
    {
        $except = false;
        try {
            $callback();
        } catch (\Exception $e) {
            $except = true;
        }
        $this->assertEquals(true, $except);
    }

    public function testWrongOptionsException()
    {
        $this->assertDBException(function () {
            $config = new \PHPixie\Config\Slice($this->pixie, 'test', array(
                'connection' => 'sqlite:'.$this->dbFile,
                'user'   => 'pixie',
                'password' => 5,
                'connection_options' => 5
            ));
            $connection = new \PHPixie\DB\Driver\Mongo\Connection($this->pixie->db->driver('PDO'), 'test', $config);
        });
    }

}
