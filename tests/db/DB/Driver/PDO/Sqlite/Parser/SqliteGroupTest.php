<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/SQL/Parser/SQLGroupTest.php');

class SqliteGroupTest extends SQLGroupTest {
	
	protected $expected = array(
		array('"a" = ?', array(1)),
		array('"a" = ? OR "b" = ? XOR NOT "c" = ?', array(1, 1, 1)),
		array('"a" = ? OR ( "b" = ? OR "c" = ? ) XOR NOT ( "d" = ? AND "e" = ? )', array(1, 1, 1, 1, 1)),
	);
	
	public function setUp() {
		parent::setUp();
		$fragment_parser = $this->db->driver('PDO')->fragment_parser('Sqlite');
		$operator_parser = $this->db->driver('PDO')->operator_parser('Sqlite', $fragment_parser);
		$this->group_parser = $this->db->driver('PDO')->group_parser('Sqlite', $operator_parser);
	}
	
}