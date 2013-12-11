<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/SQL/Parser/SQLOperatorTest.php');

class MysqlOperatorTest extends SQLOperatorTest {
	protected $expected = array(
		array('`a` = ?', array(1)),
		array('`a` = la', array()),
		array('`a` = la', array()),
		array('`a` = `b`', array()),
		array('`a` IS NULL', array()),
		array('`a` IS NOT NULL', array()),
		array('`a` <> ?', array(1)),
		array('`a` <> `c`.`b`', array()),
		array('`a` > ?', array(1)),
		array('`a` > `b`', array()),
		array('`a` LIKE ?', array('hello')),
		array('`a` REGEXP ?', array('hello')),
		array('`a` IN (?, ?)', array(1, 2)),
		array('`a` IN ( la )', array()),
		array('`a` IN ( fairy )', array(1)),
		array('`a` BETWEEN ? AND ?', array(1, 2)),
		array('`a` NOT BETWEEN ? AND ?', array(1, 2)),
		array('`a`.`b` = b', array(1)),
		array('a + b = ?', array(1))
	);
	public function setUp() {
		parent::setUp();
		$fragment = $this->db->driver('PDO')->fragment_parser('Mysql');
		$this->operator_parser = $this->db->driver('PDO')->operator_parser('Mysql', $fragment);
	}
}