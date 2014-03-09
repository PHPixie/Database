<?php
namespace PHPixieTests\DB\Driver;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\Mongo
 */
class MongoTest extends \PHPixieTests\DB\DriverTest
{
    protected $parserClass = 'PHPixie\DB\Driver\Mongo\Parser';
    protected $queryClass = 'PHPixie\DB\Driver\Mongo\Query';
    
    public function setUp()
    {
        parent::setUp();
        $this->connectionStub = $this->getMock('\PHPixie\DB\Driver\Mongo\Connection', array('config'), array(), '', null, false);
        $this->db
                ->expects($this->any())
                ->method('get')
                ->with()
                ->will($this->returnValue($this->connectionStub));
        $this->db
                ->expects($this->any())
                ->method('parser')
                ->with ('connection_name')
                ->will($this->returnValue('parser'));

        $this->connectionStub
                            ->expects($this->any())
                            ->method('config')
                            ->with()
                            ->will($this->returnValue('config'));
        $this->driver = new \PHPixie\DB\Driver\Mongo($this->db);
    }

    /**
     * @covers ::operatorParser
     */
    public function testOperatorParser()
    {
        $operatorParser = $this->driver->operatorParser();
        $this->assertInstanceOf('PHPixie\DB\Driver\Mongo\Parser\Operator', $operatorParser);
    }
    
    /**
     * @covers ::runner
     */
    public function testRunner()
    {
        $runner = $this->driver->runner();
        $this->assertInstanceOf('PHPixie\DB\Driver\Mongo\Query\Runner', $runner);
    }

    /**
     * @covers ::groupParser
     */
    public function testGroupParser()
    {
        $groupParser = $this->driver->groupParser('operatorParser');
        $this->assertInstanceOf('PHPixie\DB\Driver\Mongo\Parser\Group', $groupParser);
        $this->assertAttributeEquals('operatorParser', 'operatorParser', $groupParser);
    }

    /**
     * @covers ::buildQuery
     */
    public function testBuildQuery()
    {
        $query = $this->driver->buildQuery('connection', 'parser', 'config', 'delete');
        $this->assertInstanceOf('PHPixie\DB\Driver\Mongo\Query', $query);
        $this->assertAttributeEquals('connection', 'connection', $query);
        $this->assertAttributeEquals('parser', 'parser', $query);
        $this->assertAttributeEquals('config', 'config', $query);
        $this->assertEquals('delete', $query->getType());
    }

    /**
     * @covers ::buildParser
     */
    public function testBuildParser()
    {
        $parser = $this->driver->buildParser('config', 'groupParser');
        $this->assertInstanceOf('PHPixie\DB\Driver\Mongo\Parser', $parser);
        $this->assertAttributeEquals($this->db, 'db', $parser);
        $this->assertAttributeEquals($this->driver, 'driver', $parser);
        $this->assertAttributeEquals('config', 'config', $parser);
        $this->assertAttributeEquals('groupParser', 'groupParser', $parser);
    }

    /**
     * @covers ::buildParserInstance
     */
    public function testBuildParserInstance()
    {
        $driver = $this->getMock('\PHPixie\DB\Driver\Mongo', array('groupParser'), array($this->db));
        $driver
            ->expects($this->any())
            ->method('groupParser')
            ->with()
            ->will($this->returnValue('groupParser'));

        $parser = $driver->buildParserInstance('test');
        $this->assertInstanceOf('PHPixie\DB\Driver\Mongo\Parser', $parser);
        $this->assertAttributeEquals($this->db, 'db', $parser);
        $this->assertAttributeEquals($driver, 'driver', $parser);
        $this->assertAttributeEquals('config', 'config', $parser);
        $this->assertAttributeEquals('groupParser', 'groupParser', $parser);
    }

    /**
     * @covers ::result
     */
    public function testResult()
    {
        $result = $this->driver->result('cursor');
        $this->assertInstanceOf('PHPixie\DB\Driver\Mongo\Result', $result);
        $this->assertAttributeEquals('cursor', 'cursor', $result);
    }

    /**
     * @covers ::expandedCondition
     */
    public function testExpandedCondition()
    {
        $condition = $this->driver->expandedCondition();
        $this->assertInstanceOf('PHPixie\DB\Driver\Mongo\Conditions\Condition\Expanded', $condition);
        $this->assertEquals(array(), $condition->groups());

        $operator = new \PHPixie\DB\Conditions\Condition\Operator('a', '=', array(1));
        $condition = $this->driver->expandedCondition($operator);
        $this->assertInstanceOf('PHPixie\DB\Driver\Mongo\Conditions\Condition\Expanded', $condition);
        $this->assertEquals(array(array($operator)), $condition->groups());
    }

    /**
     * @covers ::buildConnection
     */
    public function testBuildConnection()
    {
        $config = $this->sliceStub(array(
            'connection' => 'mongodb://test:555',
            'connectionOptions' => array('connect' => false)
        ));
        
        $connection = $this->driver->buildConnection('connectionName', $config);
        $this->assertInstanceOf('PHPixie\DB\Driver\Mongo\Connection', $connection);
        
    }
}
