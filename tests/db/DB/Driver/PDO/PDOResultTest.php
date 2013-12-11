<?php

require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/ResultTest.php');

class PDOResultTest extends ResultTest {
	
	public function setUp() {
		$db = new PDO('sqlite::memory:');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->exec("CREATE TABLE fairies(id INT,name VARCHAR(255))");
		$db->exec("INSERT INTO fairies (id,name) VALUES (1,'Tinkerbell')");
		$db->exec("INSERT INTO fairies (id,name) VALUES (2, NULL)");
		$db->exec("INSERT INTO fairies (id,name) VALUES (3,'Trixie')");
		$q = $db->prepare("SELECT * from fairies");
		$q->execute();
		$this->result = new \PHPixie\DB\Driver\PDO\Result($q);
	}
	
	public function testRewind() {
		$this->assertRewindException();
	}
	
}