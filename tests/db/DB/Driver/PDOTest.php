<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/DriverTest.php');

class PDOTest extends DriverTest
{
    protected $adapterList = array('mysql', 'pgsql', 'sqlite');
    protected $parserClass = 'PHPixie\DB\Driver\PDO\Sqlite\Parser';
    protected $queryClass = 'PHPixie\DB\Driver\PDO\Query';
    public function setUp()
    {
        parent::setUp();
        $this->connectionStub = $this->getMock('\PHPixie\DB\Driver\PDO\Connection', array('config', 'adapter_name'), array(), '', null, false);
        $this->pixie-> db
                    ->expects($this->any())
                    ->method('get')
                    ->with()
                    ->will($this->returnValue($this->connectionStub));
        $this->pixie-> db
                    ->expects($this->any())
                    ->method('parser')
                    ->with ('connection_name')
                    ->will($this->returnValue('parser'));

        $this->connectionStub
                            ->expects($this->any())
                            ->method('config')
                            ->with()
                            ->will($this->returnValue('config'));
        $this->connectionStub
                            ->expects($this->any())
                            ->method('adapter_name')
                            ->with()
                            ->will($this->returnValue('Sqlite'));
        $this->driver = new \PHPixie\DB\Driver\PDO($this->pixie->db);
    }

    public function testAdapter()
    {
        foreach($this->adapterList as $name)
            $this->singleAdapterTest($name);
    }

    protected function singleAdapterTest($name)
    {
        $connection = $this->getMock('\PHPixie\DB\Driver\PDO\Connection', array('execute'), array(), '', false);
        if($name != 'sqlite')
            $connection
                        ->expects($this->once())
                        ->method('execute')
                        ->with('SET NAMES utf8')
                        ->will($this->returnValue(null));
        $adapter = $this->driver->adapter($name, 'config', $connection);
        $this->assertEquals('PHPixie\DB\Driver\PDO\\'.ucfirst($name).'\Adapter', get_class($adapter));
        $this->assertAttributeEquals('config', 'config', $adapter);
        $this->assertAttributeEquals($connection, 'connection', $adapter);
    }

    public function testFragmentParser()
    {
        foreach($this->adapterList as $name)
            $this->singleFragmentParserTest($name);
    }

    protected function singleFragmentParserTest($name)
    {
        $fragmentParser = $this->driver->fragmentParser($name);
        $this->assertEquals('PHPixie\DB\Driver\PDO\\'.ucfirst($name).'\Parser\Fragment', get_class($fragmentParser));
    }

    public function testOperatorParser()
    {
        foreach($this->adapterList as $name)
            $this->singleOperatorParserTest($name);
    }

    protected function singleOperatorParserTest($name)
    {
        $fragmentParser = $this->driver->operatorParser($name, 'fragment_parser');
        $this->assertEquals('PHPixie\DB\Driver\PDO\\'.ucfirst($name).'\Parser\Operator', get_class($fragmentParser));
        $this->assertAttributeEquals('fragment_parser', 'fragment_parser', $fragmentParser);
    }

    public function testGroupParser()
    {
        foreach($this->adapterList as $name)
            $this->singleGroupParserTest($name);
    }

    protected function singleGroupParserTest($name)
    {
        $groupParser = $this->driver->groupParser($name, 'operator_parser');
        $this->assertEquals('PHPixie\DB\Driver\PDO\\'.ucfirst($name).'\Parser\Group', get_class($groupParser));
        $this->assertAttributeEquals('operator_parser', 'operator_parser', $groupParser);
    }

    public function testBuildParser()
    {
        foreach($this->adapterList as $name)
            $this->singleBuildParserTest($name);
    }

    protected function singleBuildParserTest($name)
    {
        $parser = $this->driver->buildParser($name, 'config', 'fragment_parser', 'group_parser');
        $this->assertEquals('PHPixie\DB\Driver\PDO\\'.ucfirst($name).'\Parser', get_class($parser));
        $this->assertAttributeEquals($this->pixie->db, 'db', $parser);
        $this->assertAttributeEquals($this->driver, 'driver', $parser);
        $this->assertAttributeEquals('config', 'config', $parser);
        $this->assertAttributeEquals('fragment_parser', 'fragment_parser', $parser);
        $this->assertAttributeEquals('group_parser', 'group_parser', $parser);
    }

    public function testBuildQuery()
    {
        $query = $this->driver->buildQuery('connection', 'parser', 'config', 'delete');
        $this->assertEquals('PHPixie\DB\Driver\PDO\Query', get_class($query));
        $this->assertAttributeEquals('connection', 'connection', $query);
        $this->assertAttributeEquals('parser', 'parser', $query);
        $this->assertAttributeEquals('config', 'config', $query);
        $this->assertEquals('delete', $query->getType());
    }

    public function testResult()
    {
        $result = $this->driver->result('statement');
        $this->assertEquals('PHPixie\DB\Driver\PDO\Result', get_class($result));
        $this->assertAttributeEquals('statement', 'statement', $result);
    }

    public function testBuildConnection()
    {
        $dbFile = tempnam(sys_get_temp_dir(), 'test.sqlite');
        $config = new \PHPixie\Config\Slice($this->pixie, 'test', array(
            'connection' => 'sqlite:'.$dbFile
        ));
        $connection = $this->driver->buildConnection('connection_name', $config);
        $this->assertAttributeEquals('connection_name', 'name', $connection);
        $this->assertAttributeEquals($config, 'config', $connection);
        $reflection = new ReflectionClass("\PHPixie\DB\Driver\PDO\Connection");
        $pdoProperty = $reflection->getProperty('pdo');
        $pdoProperty->setAccessible(true);
        $pdoProperty->setValue($connection, null);
        unlink($dbFile);
    }

    public function testBuildParserInstance()
    {
        $driver = $this->getMock('\PHPixie\DB\Driver\PDO', array('fragment_parser', 'group_parser', 'operator_parser'), array($this->pixie-> db));
        $driver
            ->expects($this->any())
            ->method('fragment_parser')
            ->with()
            ->will($this->returnValue('fragment_parser'));
        $driver
            ->expects($this->any())
            ->method('group_parser')
            ->with()
            ->will($this->returnValue('group_parser'));

        $parser = $driver->buildParserInstance('test');
        $this->assertEquals('PHPixie\DB\Driver\PDO\Sqlite\Parser', get_class($parser));
        $this->assertAttributeEquals($this->pixie->db, 'db', $parser);
        $this->assertAttributeEquals($driver, 'driver', $parser);
        $this->assertAttributeEquals('config', 'config', $parser);
        $this->assertAttributeEquals('fragment_parser', 'fragment_parser', $parser);
        $this->assertAttributeEquals('group_parser', 'group_parser', $parser);
    }
}
