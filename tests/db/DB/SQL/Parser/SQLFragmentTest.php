<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/SQL/Parser/BaseSQLParserTest.php');

abstract class SQLFragmentTest extends BaseSQLParserTest {
	protected $db;
	protected $fragment_parser;
	
	protected $expected_columns;
	protected $expected_tables;
	
	public function setUp() {
		$pixie = new \PHPixie\Pixie;
		$this->db = $pixie->db = new \PHPixie\DB($pixie);
	}
	
	public function testQuote() {
		$this->assertEquals($this->quoted, $this->fragment_parser->quote('a'));
	}
	
	protected function columns(){
		$columns = array(
			'a',
			'a.b',
			'a.*',
			'*'
		);
		
		return $columns;
	}
	
	public function testAppendColumn() {
		foreach($this->columns() as $key => $column) {
			$expr = $this->db->expr();
			$expr = $this->fragment_parser->append_column($column, $expr);
			$this->assertExpression($expr, $this->expected_columns[$key]);
		}
	}
	
	protected function tables() {
	
		$tables = array(
			array('a', null),
			array('a', 'b'),
			array($this->db->expr('la', array(1)), 'b'),
			array($this->queryStub('fairy', array(1)), 'b')
		);
		
		return $tables;
	}
	
	public function testAppendTable() {
		foreach($this->tables() as $key => $table) {
			$expr = $this->db->expr();
			$expr = $this->fragment_parser->append_table($table[0], $expr, $table[1]);
			$this->assertExpression($expr, $this->expected_tables[$key]);
		}
	}
	


	protected function values() {
	
		$values = array(
			'a',
			$this->db->expr('la', array(1)),
			$this->queryStub('fairy', array(1))
		);
		
		return $values;
	}
	
	public function testAppendValue() {
		foreach($this->values() as $key => $value) {
			$expr = $this->db->expr();
			$expr = $this->fragment_parser->append_value($value, $expr);
			$this->assertExpression($expr, $this->expected_values[$key]);
		}
	}
	
}