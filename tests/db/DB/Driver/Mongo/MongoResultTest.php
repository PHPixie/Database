<?php

require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/ResultTest.php');

class MongoResultCursorStub extends ArrayObject{
	
}

class MongoResultTest extends ResultTest {
	
	public function setUp() {
		$cursor = new MongoResultCursorStub(array(
			(object) array('id' => 1, 'name' => 'Tinkerbell'),
			(object) array('id' => 2, 'name' => null),
			(object) array('id' => 3, 'name' => 'Trixie')
		));
		$cursor = $cursor->getIterator();
		$this->result = new \PHPixie\DB\Driver\Mongo\Result($cursor);
	}
	
	public function testRewind() {
		$this->assertEquals(array('id' => 1, 'name' => 'Tinkerbell'), (array) $this->result->current());
		$this->result->next();
		$this->assertEquals(array('id' => 2, 'name' => null), (array) $this->result->current());
		$this->result->next();
		$this->result->rewind();
		$this->assertEquals(array('id' => 1, 'name' => 'Tinkerbell'), (array) $this->result->current());
		$this->result->next();
		$this->testAsArray();
		$this->result->next();
		$this->result->next();
		$this->testGetColumn();
	}
	
}