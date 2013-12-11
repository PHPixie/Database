<?php

require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/SQL/Parser/BaseSQLParserTest.php');
abstract class SQLOperatorTest extends BaseSQLParserTest {
	protected $expected;
	protected $db;
	protected $operator_parser;
	
	public function setUp() {
		$pixie = new \PHPixie\Pixie;
		$this->db = $pixie->db = new \PHPixie\DB($pixie);
	}
	
	public function testParse() {
		foreach($this->conditions() as $key => $condition) {
			$parsed = $this->operator_parser->parse($condition);
			$this->assertEquals($this->expected[$key][0], $parsed->sql);
			$this->assertEquals($this->expected[$key][1], $parsed->params);
		}
	}
	
	public function testExceptions() {
		foreach($this->exception_conditions() as $condition) {
			$except = false;
			try {
				$this->operator_parser->parse($condition);
			}catch (\PHPixie\DB\Exception\Parser $e) {
				$except = true;
			}
			$this->assertEquals(true, $except);
		}
	}
	
	protected function exception_conditions() {
		$conditions = array(
			$this->operator('a', '=', array(1, 2)),
			$this->operator('a', '=*', null),
			$this->operator('a', '=*', array(1,2)),
			$this->operator('a', 'like', array('hello',1)),
			$this->operator('a', 'regexp', array('hello',1)),
			$this->operator('a', 'in', array('one')),
			$this->operator('a', 'between', array(1)),
		);
		
		return $conditions;
	}
	
	protected function conditions() {
			
		$conditions = array(
			$this->operator('a', '=', array(1)),
			$this->operator('a', '=', array($this->db->expr('la'))),
			$this->operator('a', '=*', array($this->db->expr('la'))),
			$this->operator('a', '=*', array('b')),
			$this->operator('a', '=', array(null)),
			$this->operator('a', '!=', array(null)),
			$this->operator('a', '!=', array(1)),
			$this->operator('a', '!=*', array('c.b')),
			$this->operator('a', '>', array(1)),
			$this->operator('a', '>*', array('b')),
			$this->operator('a', 'like', array('hello')),
			$this->operator('a', 'regexp', array('hello')),
			$this->operator('a', 'in', array(array(1, 2))),
			$this->operator('a', 'in', array($this->db->expr('la'))),
			$this->operator('a', 'in', array($this->queryStub('fairy',array(1)))),
			$this->operator('a', 'between', array(1, 2)),
			$this->operator('a', 'not between', array(1, 2)),
			$this->operator('a.b', '=', array($this->db->expr('b', array(1)))),
			$this->operator($this->db->expr('a + b'), '=', array(1))
		);
		
		return $conditions;
	}
	
	protected function operator($field, $operator, $values) {
		return new \PHPixie\DB\Conditions\Condition\Operator($field, $operator, $values);
	}
	
}