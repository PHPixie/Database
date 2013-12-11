<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/DriverTest.php');

class MongoTest extends DriverTest {
	protected $parserClass = 'PHPixie\DB\Driver\Mongo\Parser';
	protected $queryClass = 'PHPixie\DB\Driver\Mongo\Query';
	public function setUp() {
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
	
	public function testOperatorParser() {
		$operator_parser = $this->driver->operator_parser();
		$this->assertEquals('PHPixie\DB\Driver\Mongo\Parser\Operator', get_class($operator_parser));
	}
	
	public function testRunner() {
		$runner = $this->driver->runner();
		$this->assertEquals('PHPixie\DB\Driver\Mongo\Query\Runner', get_class($runner));
	}
	
	public function testGroupParser() {
		$group_parser = $this->driver->group_parser('operator_parser');
		$this->assertEquals('PHPixie\DB\Driver\Mongo\Parser\Group', get_class($group_parser));
		$this->assertAttributeEquals('operator_parser', 'operator_parser', $group_parser);
	}
	
	public function testBuildQuery() {
		$query = $this->driver->build_query('connection', 'parser', 'config', 'delete');
		$this->assertEquals('PHPixie\DB\Driver\Mongo\Query', get_class($query));
		$this->assertAttributeEquals('connection', 'connection', $query);
		$this->assertAttributeEquals('parser', 'parser', $query);
		$this->assertAttributeEquals('config', 'config', $query);
		$this->assertEquals('delete', $query->get_type());
	}
	
	public function testBuildParser() {
		$parser = $this->driver->build_parser('config', 'group_parser');
		$this->assertEquals('PHPixie\DB\Driver\Mongo\Parser', get_class($parser));
		$this->assertAttributeEquals($this->pixie->db, 'db', $parser);
		$this->assertAttributeEquals($this->driver, 'driver', $parser);
		$this->assertAttributeEquals('config', 'config', $parser);
		$this->assertAttributeEquals('group_parser', 'group_parser', $parser);
	}
	
	public function testBuildParserInstance() {
		$driver = $this->getMock('\PHPixie\DB\Driver\Mongo', array('group_parser'), array($this->pixie-> db));
		$driver
			->expects($this->any())
			->method('group_parser')
			->with()
			->will($this->returnValue('group_parser'));
			
		$parser = $driver->build_parser_instance('test');
		$this->assertEquals('PHPixie\DB\Driver\Mongo\Parser', get_class($parser));
		$this->assertAttributeEquals($this->pixie->db, 'db', $parser);
		$this->assertAttributeEquals($driver, 'driver', $parser);
		$this->assertAttributeEquals('config', 'config', $parser);
		$this->assertAttributeEquals('group_parser', 'group_parser', $parser);
	}
	
	public function testResult() {
		$result = $this->driver->result('cursor');
		$this->assertEquals('PHPixie\DB\Driver\Mongo\Result', get_class($result));
		$this->assertAttributeEquals('cursor', 'cursor', $result);
	}
	
	public function testExpandedCondition() {
		$condition = $this->driver->expanded_condition();
		$this->assertEquals('PHPixie\DB\Driver\Mongo\Condition\Expanded', get_class($condition));
		$this->assertEquals(array(), $condition->groups());
		
		$operator = new \PHPixie\DB\Conditions\Condition\Operator('a', '=', array(1));
		$condition = $this->driver->expanded_condition($operator);
		$this->assertEquals('PHPixie\DB\Driver\Mongo\Condition\Expanded', get_class($condition));
		$this->assertEquals(array(array($operator)), $condition->groups());
	}
	
	public function testBuildConnection() {
		$config = new \PHPixie\Config\Slice($this->pixie, 'test', array(
			'connection' => 'mongodb://test:555',
			'connection_options' => array(
					'connect'    =>  false
			)
		));
		
		$connection = $this->driver->build_connection('connection_name', $config);
		$this->assertAttributeEquals($config, 'config', $connection);
		$reflection = new ReflectionClass("\PHPixie\DB\Driver\Mongo\Connection");
	}
}