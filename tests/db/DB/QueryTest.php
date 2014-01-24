<?php

class BuilderStub {
	public $passed = array();
	public $start_group_logic;
	public $end_group_called = false;
	
	public function add_condition() {
		$this->passed[] = func_get_args();
	}
	
	public function get_conditions() {
		return array(1);
	}
	
	public function start_group($logic) {
		$this->start_group_logic = $logic;
	}
	
	public function end_group() {
		$this->end_group_called = true;
	}
}

abstract class QueryTest extends PHPUnit_Framework_TestCase {

	protected $pixie;
	protected $query;
	protected $parser;
	protected $connection;
	public $builder;
	
	protected function setUp() {
		$this->pixie = new \PHPixie\Pixie;
		$this->pixie->db = $this->getMock('\PHPixie\DB', array('condition_builder'), array($this->pixie));
		$this->pixie->db
						->expects($this->any())
						->method('condition_builder')
						->will($this->returnCallback(function() {
							return $this->builder;
						}));
		$this->parser = $this->mockParser();
		$this->query = $this->query();
		$this->builder = new BuilderStub();
	}
	
	abstract protected function  query();
	
	
	public function testData() {
		$this->getSetTest('data', array('a' =>1));
	}
	
	public function testType(){
		$this->assertEquals('select', $this->query->get_type());
		$this->assertEquals($this->query, $this->query->type('delete'));
		$this->assertEquals('delete', $this->query->get_type());
	}
	
	public function testFields(){
		$this->assertEquals(array(), $this->query->get_fields());
		$this->assertEquals($this->query, $this->query->fields(array('id')));
		$this->assertEquals(array('id'), $this->query->get_fields());
		$this->assertBuilderException(function() {
			$this->query->fields('test');
		});
	}
	
	public function testOffset(){
		$this->getSetTest('offset', 5);
		$this->assertBuilderException(function() {
			$this->query->offset('test');
		});
	}
	
	public function testLimit(){
		$this->getSetTest('limit', 5);
		$this->assertBuilderException(function() {
			$this->query->limit('test');
		});
	}
	
	public function testOrderBy(){
		$this->assertEquals(array(), $this->query->get_order_by());
		$this->assertEquals($this->query, $this->query->order_by('id', 'desc'));
		$this->assertEquals(array(array('id', 'desc')), $this->query->get_order_by());
		$this->assertEquals($this->query, $this->query->order_by('name'));
		$this->assertEquals(array(array('id','desc'),array('name','asc')), $this->query->get_order_by());
		$this->assertBuilderException(function() {
			$this->query->order_by('name', 'test');
		});
	}
	
	public function testWhere() {
		$this->builderTest('where');
	}
	
	protected function getSetTest($method, $param, $default = null) {
		$this->assertEquals($default, call_user_func_array(array($this->query, 'get_'.$method), array()));
		$this->assertEquals($this->query, call_user_func_array(array($this->query, $method), array($param)));
		$this->assertEquals($param, call_user_func_array(array($this->query, 'get_'.$method), array()));
	}
	
	protected function builderTest($name, $test_get = true) {
		
		
		$this->assertEquals($this->query, call_user_func(array($this->query, $name), 'id', 1));
		$this->assertEquals(array('and', false, array('id', 1)), end($this->builder->passed));
		
		$this->assertEquals($this->query, call_user_func(array($this->query, 'or_'.$name), 'id', 1));
		$this->assertEquals(array('or', false, array('id', 1)), end($this->builder->passed));
		
		$this->assertEquals($this->query, call_user_func(array($this->query, 'xor_'.$name), 'id', 1));
		$this->assertEquals(array('xor', false, array('id', 1)), end($this->builder->passed));
		
		$this->assertEquals($this->query, call_user_func(array($this->query, $name.'_not'), 'id', 1));
		$this->assertEquals(array('and', true, array('id', 1)), end($this->builder->passed));
		
		$this->assertEquals($this->query, call_user_func(array($this->query, 'or_'.$name.'_not'), 'id', 1));
		$this->assertEquals(array('or', true, array('id', 1)), end($this->builder->passed));
		
		$this->assertEquals($this->query, call_user_func(array($this->query, 'xor_'.$name.'_not'), 'id', 1));
		$this->assertEquals(array('xor', true, array('id', 1)), end($this->builder->passed));
		
		$this->assertEquals($this->query, call_user_func(array($this->query, 'start_'.$name.'_group')));
		$this->assertEquals($this->query, call_user_func(array($this->query, 'end_'.$name.'_group')));
		$this->assertEquals('and', $this->builder->start_group_logic);
		$this->assertEquals(true, $this->builder->end_group_called);
		
		$this->builder->start_group_logic = null;
		$this->builder->end_group_called = false;
		
		$this->assertEquals($this->query, call_user_func(array($this->query, 'start_'.$name.'_group'), 'or'));
		$this->assertEquals($this->query, call_user_func(array($this->query, 'end_'.$name.'_group')));
		$this->assertEquals('or', $this->builder->start_group_logic);
		$this->assertEquals(true, $this->builder->end_group_called);
		
		if($test_get)
			$this->assertEquals(array(1), $this->query->get_conditions($name));
		
	}
	
	public function testGenericBuilder() {
		$this->assertBuilderException(function() {
			$this->query->_and('a', 1);
		});
		$this->assertBuilderException(function() {
			$this->query->start_group();
		});
		$this->query->where('a', 1);
		$this->genericBuilderTest($this->builder);
	}
	protected function genericBuilderTest($builder) {
	
		$this->assertEquals($this->query, $this->query->_and('id', 1));
		$this->assertEquals(array('and', false, array('id', 1)), end($builder->passed));
		
		$this->assertEquals($this->query, $this->query->_or('id', 1));
		$this->assertEquals(array('or', false, array('id', 1)), end($builder->passed));
		
		$this->assertEquals($this->query, $this->query->_xor('id', 1));
		$this->assertEquals(array('xor', false, array('id', 1)), end($builder->passed));
		
		$this->assertEquals($this->query, $this->query->_and_not('id', 1));
		$this->assertEquals(array('and', true, array('id', 1)), end($builder->passed));
		
		$this->assertEquals($this->query, $this->query->_or_not('id', 1));
		$this->assertEquals(array('or', true, array('id', 1)), end($builder->passed));
		
		$this->assertEquals($this->query, $this->query->_xor_not('id', 1));
		$this->assertEquals(array('xor', true, array('id', 1)), end($builder->passed));
		
		$this->assertEquals($this->query, call_user_func(array($this->query, 'start_group'), 'or'));
		$this->assertEquals($this->query, call_user_func(array($this->query, 'end_group')));
		$this->assertEquals('or', $builder->start_group_logic);
		$this->assertEquals(true, $builder->end_group_called);
	}
	
	protected function assertBuilderException($callback) {
		$except = false;
		try {
			$callback();
		}catch (\PHPixie\DB\Exception\Builder $e) {
			$except = true;
		}
		$this->assertEquals(true, $except);
	}
	
	public function testParse() {
		$query = $this->query();
		$this->parser
				->expects($this->any())
				->method('parse')
				->with ($query)
				->will($this->returnValue('a'));
		$this->assertEquals('a', $query->parse());
	}
	
	public abstract function testExecute();
}