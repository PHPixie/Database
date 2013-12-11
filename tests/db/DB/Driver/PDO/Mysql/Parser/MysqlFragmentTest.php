<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/SQL/Parser/SQLFragmentTest.php');

class MysqlFragmentTest extends SQLFragmentTest {
	
	protected $quoted = '`a`';
	protected $expected_columns = array(
		array('`a`', array()),
		array('`a`.`b`', array()),
		array('`a`.*', array()),
		array('*', array())
	);
	
	protected $expected_tables = array(
		array('`a`', array()),
		array('`a` AS `b`', array()),
		array('( la ) AS `b`', array(1)),
		array('( fairy ) AS `b`', array(1)),
	);
	
	protected $expected_values = array(
		array('?', array('a')),
		array('la', array(1)),
		array('( fairy )', array(1)),
	);
	
	public function setUp() {
		parent::setUp();
		$this->fragment_parser = $this->db->driver('PDO')->fragment_parser('Mysql');
	}
	
}