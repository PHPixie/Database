<?php
namespace PHPixieTests\Database\Driver;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO
 */
class PDOTest extends \PHPixieTests\Database\DriverTest
{
    protected $adapterList = array('mysql', 'pgsql', 'sqlite');
    protected $parserClass = '\PHPixie\Database\Driver\PDO\Adapter\Sqlite\Parser';
    protected $queryClass = '\PHPixie\Database\Driver\PDO\Query';
    protected $builderClass = '\PHPixie\Database\Driver\PDO\Query\Builder';
    
    public function setUp()
    {
        parent::setUp();
        $this->connectionStub = $this->getMock('\PHPixie\Database\Driver\PDO\Connection', array('config', 'adapterName'), array(), '', null, false);
        $this->database
                ->expects($this->any())
                ->method('get')
                ->with()
                ->will($this->returnValue($this->connectionStub));
        $this->database
                ->expects($this->any())
                ->method('parser')
                ->with ('connectionName')
                ->will($this->returnValue('parser'));

        $this->connectionStub
                        ->expects($this->any())
                        ->method('config')
                        ->with()
                        ->will($this->returnValue('config'));
        $this->connectionStub
                        ->expects($this->any())
                        ->method('adapterName')
                        ->with()
                        ->will($this->returnValue('Sqlite'));
        $this->driver = new \PHPixie\Database\Driver\PDO($this->database);
    }

    
    /**
     * @covers ::adapter
     */
    public function testAdapter()
    {
        foreach($this->adapterList as $name)
            $this->singleAdapterTest($name);
    }

    protected function singleAdapterTest($name)
    {
        $connection = $this->getMock('\PHPixie\Database\Driver\PDO\Connection', array('execute'), array(), '', false);
        if($name != 'sqlite')
            $connection
                        ->expects($this->once())
                        ->method('execute')
                        ->with('SET NAMES utf8')
                        ->will($this->returnValue(null));
        $adapter = $this->driver->adapter($name, 'config', $connection);
        $this->assertInstanceOf('PHPixie\Database\Driver\PDO\Adapter\\'.ucfirst($name), $adapter);
        $this->assertAttributeEquals('config', 'config', $adapter);
        $this->assertAttributeEquals($connection, 'connection', $adapter);
    }

    /**
     * @covers ::fragmentParser
     */
    public function testFragmentParser()
    {
        foreach($this->adapterList as $name)
            $this->singleFragmentParserTest($name);
    }

    protected function singleFragmentParserTest($name)
    {
        $fragmentParser = $this->driver->fragmentParser($name);
        $this->assertInstanceOf('PHPixie\Database\Driver\PDO\Adapter\\'.ucfirst($name).'\Parser\Fragment', $fragmentParser);
    }

    /**
     * @covers ::operatorParser
     */
    public function testOperatorParser()
    {
        foreach($this->adapterList as $name)
            $this->singleOperatorParserTest($name);
    }

    protected function singleOperatorParserTest($name)
    {
        $fragmentParser = $this->driver->operatorParser($name, 'fragmentParser');
        $this->assertInstanceOf('PHPixie\Database\Driver\PDO\Adapter\\'.ucfirst($name).'\Parser\Operator', $fragmentParser);
        $this->assertAttributeEquals('fragmentParser', 'fragmentParser', $fragmentParser);
    }

    /**
     * @covers ::groupParser
     */
    public function testGroupParser()
    {
        foreach($this->adapterList as $name)
            $this->singleGroupParserTest($name);
    }

    protected function singleGroupParserTest($name)
    {
        $groupParser = $this->driver->groupParser($name, 'operatorParser');
        $this->assertInstanceOf('PHPixie\Database\Driver\PDO\Adapter\\'.ucfirst($name).'\Parser\Group', $groupParser);
        $this->assertAttributeEquals('operatorParser', 'operatorParser', $groupParser);
    }

    /**
     * @covers ::buildParser
     */
    public function testBuildParser()
    {
        foreach($this->adapterList as $name)
            $this->singleBuildParserTest($name);
    }

    protected function singleBuildParserTest($name)
    {
        $parser = $this->driver->buildParser($name, 'config', 'fragmentParser', 'groupParser');
        $this->assertInstanceOf('PHPixie\Database\Driver\PDO\Adapter\\'.ucfirst($name).'\Parser', $parser);
        $this->assertAttributeEquals($this->database, 'database', $parser);
        $this->assertAttributeEquals($this->driver, 'driver', $parser);
        $this->assertAttributeEquals('config', 'config', $parser);
        $this->assertAttributeEquals('fragmentParser', 'fragmentParser', $parser);
        $this->assertAttributeEquals('groupParser', 'groupParser', $parser);
    }

    /**
     * @covers ::buildQuery
     */
    public function testBuildQuery()
    {
        $query = $this->driver->buildQuery('delete', 'connection', 'parser', 'builder');
        $this->assertInstanceOf('PHPixie\Database\Driver\PDO\Query\Type\Delete', $query);
        $this->assertAttributeEquals('connection', 'connection', $query);
        $this->assertAttributeEquals('parser', 'parser', $query);
        $this->assertAttributeEquals('builder', 'builder', $query);
        $this->assertEquals('delete', $query->type());
    }

    /**
     * @covers ::result
     */
    public function testResult()
    {
        $result = $this->driver->result('statement');
        $this->assertInstanceOf('PHPixie\Database\Driver\PDO\Result', $result);
        $this->assertAttributeEquals('statement', 'statement', $result);
    }

    /**
     * @covers ::buildConnection
     */
    public function testBuildConnection()
    {
        $databaseFile = tempnam(sys_get_temp_dir(), 'test.sqlite');
        $config = $this->sliceStub(array(
            'connection' => 'sqlite:'.$databaseFile
        ));
        $connection = $this->driver->buildConnection('connectionName', $config);
        $this->assertAttributeEquals('connectionName', 'name', $connection);
        $this->assertAttributeEquals($config, 'config', $connection);
        $reflection = new \ReflectionClass("\PHPixie\Database\Driver\PDO\Connection");
        $pdoProperty = $reflection->getProperty('pdo');
        $pdoProperty->setAccessible(true);
        $pdoProperty->setValue($connection, null);
        unlink($databaseFile);
    }

    /**
     * @covers ::buildParserInstance
     */
    public function testBuildParserInstance()
    {
        $driver = $this->getMock('\PHPixie\Database\Driver\PDO', array('fragmentParser', 'groupParser'), array($this->database));
        $driver
            ->expects($this->any())
            ->method('fragmentParser')
            ->with()
            ->will($this->returnValue('fragmentParser'));
        $driver
            ->expects($this->any())
            ->method('groupParser')
            ->with()
            ->will($this->returnValue('groupParser'));

        $parser = $driver->buildParserInstance('test');
        $this->assertInstanceOf($this->parserClass, $parser);
        $this->assertAttributeEquals($this->database, 'database', $parser);
        $this->assertAttributeEquals($driver, 'driver', $parser);
        $this->assertAttributeEquals('config', 'config', $parser);
        $this->assertAttributeEquals('fragmentParser', 'fragmentParser', $parser);
        $this->assertAttributeEquals('groupParser', 'groupParser', $parser);
    }
    
    /**
     * @covers ::buildQueryBuilder
     */
    public function testBuildQueryBuilder()
    {
        $builder = $this->driver->buildQueryBuilder('test');
        $this->assertInstanceOf('\PHPixie\Database\Driver\PDO\Query\Builder', $builder);
        $this->assertAttributeEquals('test', 'conditions', $builder);
    }
}
