<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/SQL/Parser/SQLGroupTest.php');

class PgsqlGroupTest extends SQLGroupTest {
	
	protected $expected = array(
		array('"a" = ?', array(1)),
		array('"a" = ? OR "b" = ? XOR NOT "c" = ?', array(1, 1, 1)),
		array('"a" = ? OR ( "b" = ? OR "c" = ? ) XOR NOT ( "d" = ? AND "e" = ? )', array(1, 1, 1, 1, 1)),
	);
	
	public function setUp() {
		parent::setUp();
		$fragment_parser = $this->db->driver('PDO')->fragment_parser('Pgsql');
		$operator_parser = $this->db->driver('PDO')->operator_parser('Pgsql', $fragment_parser);
		$this->group_parser = $this->db->driver('PDO')->group_parser('Pgsql', $operator_parser);
	}
	
}