<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/DriverTest.php');

class MongoTest extends DriverTest
{
    protected $parserClass = 'PHPixie\DB\Driver\Mongo\Parser';
    protected $queryClass = 'PHPixie\DB\Driver\Mongo\Query';
    public function setUp()
    {
        parent::setUp();
        $this->connectionStub = $this->getMock('\PHPixie\DB\Driver\Mongo\Connection', array('config'), array(), '', null, false);
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
        $this->driver = new \PHPixie\DB\Driver\Mongo($this->pixie->db);
    }

    public function testOperatorParser()
    {
        $operatorParser = $this->driver->operatorParser();
        $this->assertEquals('PHPixie\DB\Driver\Mongo\Parser\Operator', get_class($operatorParser));
    }

    public function testRunner()
    {
        $runner = $this->driver->runner();
        $this->assertEquals('PHPixie\DB\Driver\Mongo\Query\Runner', get_class($runner));
    }

    public function testGroupParser()
    {
        $groupParser = $this->driver->groupParser('operator_parser');
        $this->assertEquals('PHPixie\DB\Driver\Mongo\Parser\Group', get_class($groupParser));
        $this->assertAttributeEquals('operator_parser', 'operator_parser', $groupParser);
    }

    public function testBuildQuery()
    {
        $query = $this->driver->buildQuery('connection', 'parser', 'config', 'delete');
        $this->assertEquals('PHPixie\DB\Driver\Mongo\Query', get_class($query));
        $this->assertAttributeEquals('connection', 'connection', $query);
        $this->assertAttributeEquals('parser', 'parser', $query);
        $this->assertAttributeEquals('config', 'config', $query);
        $this->assertEquals('delete', $query->getType());
    }

    public function testBuildParser()
    {
        $parser = $this->driver->buildParser('config', 'group_parser');
        $this->assertEquals('PHPixie\DB\Driver\Mongo\Parser', get_class($parser));
        $this->assertAttributeEquals($this->pixie->db, 'db', $parser);
        $this->assertAttributeEquals($this->driver, 'driver', $parser);
        $this->assertAttributeEquals('config', 'config', $parser);
        $this->assertAttributeEquals('group_parser', 'group_parser', $parser);
    }

    public function testBuildParserInstance()
    {
        $driver = $this->getMock('\PHPixie\DB\Driver\Mongo', array('group_parser'), array($this->pixie-> db));
        $driver
            ->expects($this->any())
            ->method('group_parser')
            ->with()
            ->will($this->returnValue('group_parser'));

        $parser = $driver->buildParserInstance('test');
        $this->assertEquals('PHPixie\DB\Driver\Mongo\Parser', get_class($parser));
        $this->assertAttributeEquals($this->pixie->db, 'db', $parser);
        $this->assertAttributeEquals($driver, 'driver', $parser);
        $this->assertAttributeEquals('config', 'config', $parser);
        $this->assertAttributeEquals('group_parser', 'group_parser', $parser);
    }

    public function testResult()
    {
        $result = $this->driver->result('cursor');
        $this->assertEquals('PHPixie\DB\Driver\Mongo\Result', get_class($result));
        $this->assertAttributeEquals('cursor', 'cursor', $result);
    }

    public function testExpandedCondition()
    {
        $condition = $this->driver->expandedCondition();
        $this->assertEquals('PHPixie\DB\Driver\Mongo\Condition\Expanded', get_class($condition));
        $this->assertEquals(array(), $condition->groups());

        $operator = new \PHPixie\DB\Conditions\Condition\Operator('a', '=', array(1));
        $condition = $this->driver->expandedCondition($operator);
        $this->assertEquals('PHPixie\DB\Driver\Mongo\Condition\Expanded', get_class($condition));
        $this->assertEquals(array(array($operator)), $condition->groups());
    }

    public function testBuildConnection()
    {
        $config = new \PHPixie\Config\Slice($this->pixie, 'test', array(
            'connection' => 'mongodb://test:555',
            'connection_options' => array(
                    'connect'    =>  false
            )
        ));

        $connection = $this->driver->buildConnection('connection_name', $config);
        $this->assertAttributeEquals($config, 'config', $connection);
        $reflection = new ReflectionClass("\PHPixie\DB\Driver\Mongo\Connection");
    }
}
