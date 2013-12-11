<?php

class BuilderTest extends PHPUnit_Framework_TestCase {
	
	protected $builder;
	protected $pixie;
	
	public function setUp() {
		$this->pixie = new \PHPixie\Pixie;
		$this->pixie-> db = new \PHPixie\DB($this->pixie);
		$this->builder = new \PHPixie\DB\Conditions\Builder($this->pixie->db, '=');
	}
	
	public function testConditions() {
		$this->builder
					->_and('a', 1)
					->_or('a', '>', 1)
					->_xor('a', 1)
					->_and_not('a', 1)
					->_or_not('a', 1)
					->_xor_not('a', 1)
					->_or(function($builder) {
						$builder->_or('a', 1);
					})
					->_and_not(function($builder) {
						$builder->_and_not('a', 1);
					});
					
		$this->assertConditions(array(
			array('and', false, 'a', '=', array(1)),
			array('or', false, 'a', '>', array(1)),
			array('xor', false, 'a', '=', array(1)),
			array('and', true, 'a', '=', array(1)),
			array('or', true, 'a', '=', array(1)),
			array('xor', true, 'a', '=', array(1)),
			array('or', false, array(
					array('or', false, 'a', '=', array(1))
				)
			),
			array('and', true, array(
					array('and', true, 'a', '=', array(1))
				)
			)
		));
	}
	
	public function testAddConditions() {
		$this->builder
					->add_condition('and', false, array('a', 1))
					->add_condition('or', false, array('a', '>', 1))
					->add_condition('xor', false, array('a', 1))
					->add_condition('and', true, array('a', 1))
					->add_condition('or', true, array('a', 1))
					->add_condition('xor', true, array('a', 1))
					->add_condition('or', false, array(function($builder) {
						$builder->_or('a', 1);
					}))
					->start_group('and_not')
						->add_condition('and', true, array('a', 1))
					->end_group();
					
					
		$this->assertConditions(array(
			array('and', false, 'a', '=', array(1)),
			array('or', false, 'a', '>', array(1)),
			array('xor', false, 'a', '=', array(1)),
			array('and', true, 'a', '=', array(1)),
			array('or', true, 'a', '=', array(1)),
			array('xor', true, 'a', '=', array(1)),
			array('or', false, array(
					array('or', false, 'a', '=', array(1))
				)
			),
			array('and', true, array(
					array('and', true, 'a', '=', array(1))
				)
			)
		));
	}
	
	public function testNested() {
		$this->builder
					->_and('a', 1)
					->_or(function($builder) {
						$builder
							->_and('a', 2)
							->_or(function($builder) {
								$builder
									->_and('a', 3)
									->start_group('xor_not')
										->_and('a', 4)
									->end_group();
							});
					});
					
		$this->assertConditions(array(
			array('and', false, 'a', '=', array(1)),
			array('or', false, array(
				array('and', false, 'a', '=', array(2)),
				array('or', false, array(
					array('and', false, 'a', '=', array(3)),
					array('xor', true, array(
						array('and', false, 'a', '=', array(4)),
					))
				))
			))
		));
	}
	
	
	public function testStartGroupException() {
		$this->assertException(function() {
			$this->builder->start_group('test');
		});
	}
	
	public function testEndGroupException() {
		$this->assertException(function() {
			$this->builder->end_group();
		});
	}
	
	public function testNestedEndGroupException() {
		$this->assertException(function() {
			$this->builder->start_group('and')->end_group()->end_group();
		});
	}
	
	public function testSingleArgumentException() {
		$this->assertException(function() {
			$this->builder->_and('a');
		});
	}
	
	public function NoArgumentsException() {
		$this->assertException(function() {
			$this->builder->_and();
		});
	}
	
	protected function assertException($callback) {
		$except = false;
		try {
			$callback();
		}catch (\PHPixie\DB\Exception $e) {
			$except = true;
		}
		
		$this->assertEquals(true, $except);
	}
	
	protected function assertConditions($expected) {
		$this->assertConditionArray($this->builder->get_conditions(), $expected);
	}
	
	protected function assertConditionArray($conditions, $expected) {
		foreach($conditions as $key => $condition) {
			$e = $expected[$key];

			$this->assertEquals($e[0], $condition->logic);
			$this->assertEquals($e[1], $condition->negated());
			if ($condition instanceof \PHPixie\DB\Conditions\Condition\Operator) {
				$this->assertEquals($e[2], $condition->field);
				$this->assertEquals($e[3], $condition->operator);
				$this->assertEquals($e[4], $condition->values);
			}else {
				$this->assertConditionArray($condition->conditions(), $e[2]);
			}
		}
	}
}
