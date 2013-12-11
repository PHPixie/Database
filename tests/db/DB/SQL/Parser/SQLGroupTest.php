<?php

require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/SQL/Parser/BaseSQLParserTest.php');
abstract class SQLGroupTest extends BaseSQLParserTest {
	
	protected $db;
	protected $group_parser;
	protected $expected;
	
	public function setUp() {
		$pixie = new \PHPixie\Pixie;
		$this->db = $pixie-> db = new \PHPixie\DB($pixie);
	}
	
	public function groups() {
		$groups = array(
			$this->builder()->_and('a',1)->get_conditions(),
			$this->builder()
						->_and('a', 1)
						->_or('b', 1)
						->_xor_not('c', 1)
						->get_conditions(),
			$this->builder()
						->_and('a', 1)
						->_or(function($builder) {
							$builder
								->_and('b', 1)
								->_or('c', 1);
						})
						->_xor_not(function($builder) {
							$builder
								->_and('d', 1)
								->_and('e', 1);
						})
						->get_conditions(),
		);
		return $groups;
	}
	public function testParse() {
		foreach($this->groups() as $key => $group) {
			$parsed = $this->group_parser->parse($group);
			$this->assertExpression($parsed, $this->expected[$key]);
		}
	}
	
	public function testExceptions() {
		foreach($this->exception_groups() as $group) {
			$except = false;
			try {
				$this->group_parser->parse($group);
			}catch (\PHPixie\DB\Exception\Parser $e) {
				$except = true;
			}
			$this->assertEquals(true, $except);
		}
	}
	
	protected function exception_groups() {
		$conditions = array(
			array($this->getMock('\PHPixie\DB\Conditions\Condition'))
		);
		
		return $conditions;
	}
	
	protected function builder() {
		return new PHPixie\DB\Conditions\Builder($this->db);
	}
	
	
}