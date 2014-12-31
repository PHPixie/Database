<?php
namespace PHPixieTests\Database\Driver;

if(!class_exists('\MongoClient'))
    require_once(__DIR__.'/Mongo/ConnectionTestFiles/MongoClient.php');

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo
 */
class MongoTest extends \PHPixieTests\Database\DriverTest
{
    protected $parserClass = 'PHPixie\Database\Driver\Mongo\Parser';
    protected $queryClass = 'PHPixie\Database\Driver\Mongo\Query';
    protected $builderClass = '\PHPixie\Database\Driver\Mongo\Query\Builder';

    public function setUp()
    {
        parent::setUp();
        $this->connectionStub = $this->getMock('\PHPixie\Database\Driver\Mongo\Connection', array('config'), array(), '', null, false);
        $this->database
                ->expects($this->any())
                ->method('get')
                ->with()
                ->will($this->returnValue($this->connectionStub));
        $this->database
                ->expects($this->any())
                ->method('parser')
                ->with('connectionName')
                ->will($this->returnValue('parser'));

        $this->connectionStub
                            ->expects($this->any())
                            ->method('config')
                            ->with()
                            ->will($this->returnValue('config'));
        $this->driver = new \PHPixie\Database\Driver\Mongo($this->database);
    }

    /**
     * @covers ::operatorParser
     */
    public function testOperatorParser()
    {
        $operatorParser = $this->driver->operatorParser();
        $this->assertInstanceOf('PHPixie\Database\Driver\Mongo\Parser\Operator', $operatorParser);
    }

    /**
     * @covers ::runner
     */
    public function testRunner()
    {
        $runner = $this->driver->runner();
        $this->assertInstanceOf('PHPixie\Database\Driver\Mongo\Query\Runner', $runner);
    }

    /**
     * @covers ::groupParser
     */
    public function testGroupParser()
    {
        $groupParser = $this->driver->groupParser();
        $this->assertSame($groupParser, $this->driver->groupParser());
    }

    /**
     * @covers ::buildGroupParserInstance
     */
    public function testBuildGroupParserInstance()
    {
        $groupParser = $this->driver->groupParser();
        $this->assertInstanceOf('PHPixie\Database\Driver\Mongo\Parser\Group', $groupParser);
    }

    /**
     * @covers ::buildQuery
     */
    public function testBuildQuery()
    {
        $query = $this->driver->buildQuery('delete', 'connection', 'parser', 'builder');
        $this->assertInstanceOf('PHPixie\Database\Driver\Mongo\Query\Type\Delete', $query);
        $this->assertAttributeEquals('connection', 'connection', $query);
        $this->assertAttributeEquals('parser', 'parser', $query);
        $this->assertAttributeEquals('builder', 'builder', $query);
        $this->assertEquals('delete', $query->type());
    }

    /**
     * @covers ::buildParser
     */
    public function testBuildParser()
    {
        $parser = $this->driver->buildParser('config', 'groupParser');
        $this->assertInstanceOf('PHPixie\Database\Driver\Mongo\Parser', $parser);
        $this->assertAttributeEquals($this->database, 'database', $parser);
        $this->assertAttributeEquals($this->driver, 'driver', $parser);
        $this->assertAttributeEquals('config', 'config', $parser);
        $this->assertAttributeEquals('groupParser', 'groupParser', $parser);
    }

    /**
     * @covers ::buildParserInstance
     */
    public function testBuildParserInstance()
    {
        $driver = $this->getMock('\PHPixie\Database\Driver\Mongo', array('groupParser'), array($this->database));
        $driver
            ->expects($this->any())
            ->method('groupParser')
            ->with()
            ->will($this->returnValue('groupParser'));

        $parser = $driver->buildParserInstance('test');
        $this->assertInstanceOf('PHPixie\Database\Driver\Mongo\Parser', $parser);
        $this->assertAttributeEquals($this->database, 'database', $parser);
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
        $this->assertInstanceOf('PHPixie\Database\Driver\Mongo\Result', $result);
        $this->assertAttributeEquals('cursor', 'cursor', $result);
    }

    /**
     * @covers ::expandedGroup
     */
    public function testExpandedCondition()
    {
        $condition = $this->driver->expandedGroup();
        $this->assertInstanceOf('PHPixie\Database\Driver\Mongo\Parser\Group\ExpandedGroup', $condition);
        $this->assertEquals(array(), $condition->groups());

        $operator = new \PHPixie\Database\Conditions\Condition\Field\Operator('a', '=', array(1));
        $condition = $this->driver->expandedGroup($operator);
        $this->assertInstanceOf('PHPixie\Database\Driver\Mongo\Parser\Group\ExpandedGroup', $condition);
        $this->assertEquals(array(array($operator)), $condition->groups());
    }

    /**
     * @covers ::buildConnection
     */
    public function testBuildConnection()
    {
        $config = $this->sliceStub(array(
            'connectionOptions' => array('connect' => false),
            'user' => null,
            'password' => null,
        ));

        $connection = $this->driver->buildConnection('connectionName', $config);
        $this->assertInstanceOf('PHPixie\Database\Driver\Mongo\Connection', $connection);

    }

    /**
     * @covers ::buildQueryBuilder
     */
    public function testBuildQueryBuilder()
    {
        $documentConditions = $this->quickMock('\PHPixie\Database\Type\Document\Conditions', array());
        $values = $this->quickMock('\PHPixie\Database\Values', array());
        
        $builder = $this->driver->buildQueryBuilder($documentConditions, $values);
        $this->assertInstanceOf('\PHPixie\Database\Driver\Mongo\Query\Builder', $builder);
        
        $this->assertAttributeSame($documentConditions, 'containerBuilder', $builder);
        $this->assertAttributesame($values, 'valueBuilder', $builder);
    }

}
