<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/QueryTest.php');

abstract class SQLQueryTest extends QueryTest {
	
	protected $join;
	protected $resultClass;
	
	public function setUp() {
		parent::setUp();
		$this->connection = $this->mockConnection();
	}
	
	public function testTable(){
		$this->assertEquals(null, $this->query->get_table());
		$this->assertEquals($this->query, $this->query->table('a'));
		$this->assertEquals(array('table'=>'a', 'alias' => null), $this->query->get_table());
		$this->assertEquals($this->query, $this->query->table('b', 'c'));
		$this->assertEquals(array('table'=>'b', 'alias' => 'c'), $this->query->get_table());
	}
	
	public function testGroupBy() {
		$this->assertEquals(array(), $this->query->get_group_by());
		$this->assertEquals($this->query, $this->query->group_by('id'));
		$this->assertEquals(array('id'), $this->query->get_group_by());
		$this->assertEquals($this->query, $this->query->group_by('name'));
		$this->assertEquals(array('id', 'name'), $this->query->get_group_by());
	}
	
	public function testJoin() {
		$this->query->join('fairies', 'pixies');
		$this->assertEquals(array(
			array(
				'builder' => $this->builder,
				'table' => 'fairies',
				'alias' => 'pixies',
				'type'  => 'inner'
			)
		), $this->query->get_joins());
		$this->query->join('test', null, 'left');
		$this->assertEquals(array(
			array(
				'builder' => $this->builder,
				'table' => 'fairies',
				'alias' => 'pixies',
				'type'  => 'inner'
			),
			array(
				'builder' => $this->builder,
				'table' => 'test',
				'alias' =>  null,
				'type'  => 'left'
			)
		), $this->query->get_joins());
		$this->query->join('test2');
		$this->assertEquals(array(
			array(
				'builder' => $this->builder,
				'table' => 'fairies',
				'alias' => 'pixies',
				'type'  => 'inner'
			),
			array(
				'builder' => $this->builder,
				'table' => 'test',
				'alias' =>  null,
				'type'  => 'left'
			),
			array(
				'builder' => $this->builder,
				'table' => 'test2',
				'alias' =>  null,
				'type'  => 'inner'
			)
		), $this->query->get_joins());
	}
	
	public function testUnions() {
		$this->assertEquals($this->query, $this->query->union($this->query));
		$this->assertEquals(array(
				array($this->query, false)
			), $this->query->get_unions());
		
		$this->assertEquals($this->query, $this->query->union($this->query, true));
		$this->assertEquals(array(
				array($this->query, false),
				array($this->query, true)
			), $this->query->get_unions());
	}
	
	public function testHaving() {
		$this->builderTest('having');
	}
	
	public function testOn() {
		$self = $this;
		$this->pixie->db
					->expects($this->any())
					->method('condition_builder')
					->with('=*')
					->will($this->returnCallback(function() use($self) {
						return $self->builder;
					}));
		$this->assertBuilderException(function(){
			$this->query->on('id', 1);
		});
		$this->query->join('fairies');
		$this->builderTest('on', false);
	}
	
	public function testExecute() {
		$query = $this->query();
		$this->parser
				->expects($this->any())
				->method('parse')
				->with ($query)
				->will($this->returnValue(new \PHPixie\DB\SQL\Expression('pixie', array(5))));
				
		$this->connection
				->expects($this->any())
				->method('execute')
				->with ('pixie', array(5))
				->will($this->returnValue('a'));
		$this->assertEquals('a', $query->execute());
	}
	
	public function testExecuteCount() {
		$query = $this->query('count');
		$result = $this->getMock($this->resultClass, array('get'), array(), '', null, false);
		$this->parser
				->expects($this->any())
				->method('parse')
				->with ($query)
				->will($this->returnValue(new \PHPixie\DB\SQL\Expression('pixie', array(5))));
				
		$this->connection
				->expects($this->any())
				->method('execute')
				->with ('pixie', array(5))
				->will($this->returnValue($result));
		$result
				->expects($this->once())
				->method('get')
				->with('count')
				->will($this->returnValue(5));
		$this->assertEquals(5, $query->execute());
	}
	
	public function testGenericBuilder() {
		$where_builder = new BuilderStub();
		$having_builder = new BuilderStub();
		$on_builder1 = new BuilderStub();
		$on_builder2 = new BuilderStub();
		$this->pixie->db = $this->getMock('\PHPixie\DB', array('condition_builder'), array($this->pixie));
		$this->pixie->db
					->expects($this->at(0))
					->method('condition_builder')
					->will($this->returnCallback(function() use($where_builder) {
						return $where_builder;
					}));
		$this->pixie->db
					->expects($this->at(1))
					->method('condition_builder')
					->will($this->returnCallback(function() use($having_builder) {
						return $having_builder;
					}));
					
		$this->pixie->db
					->expects($this->at(2))
					->method('condition_builder')
					->will($this->returnCallback(function() use($on_builder1) {
						return $on_builder1;
					}));
					
		$this->pixie->db
					->expects($this->at(3))
					->method('condition_builder')
					->will($this->returnCallback(function() use($on_builder2) {
						return $on_builder2;
					}));
					
		$this->assertBuilderException(function() {
			$this->query->_and('a', 1);
		});
		
		$this->query = $this->query();
		$this->query->where('a', 1);
		$this->assertAttributeEquals($where_builder, 'last_used_builder', $this->query);
		$this->genericBuilderTest($where_builder);
		
		$this->query->having('a', 1);
		$this->assertAttributeEquals($having_builder, 'last_used_builder', $this->query);
		$this->genericBuilderTest($having_builder);
		
		$this->query->join('pixies');
		$this->query->on('a.id', 'pixies.id');
		$this->assertAttributeEquals($on_builder1, 'last_used_builder', $this->query);
		$this->genericBuilderTest($on_builder1);
		
		$this->query->join('pixies');
		$this->query->on('a.id', 'pixies.id');
		$this->assertAttributeEquals($on_builder2, 'last_used_builder', $this->query);
		$this->genericBuilderTest($on_builder2);

	}
}