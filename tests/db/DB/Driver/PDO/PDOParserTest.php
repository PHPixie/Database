<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/SQL/SQLParserTest.php');

class PDOParserTest extends SQLParserTest {
	
	protected $adapter;
	
	protected function setUp(){
		parent::setUp();
		$this->parser = $this->parser();
	}
	
	protected function parser() {
		$driver = $this->pixie->db->driver('PDO');
		$fragment_parser = $driver->fragment_parser($this->adapter);
		$operator_parser = $driver->operator_parser($this->adapter, $fragment_parser);
		$group_parser    = $driver->group_parser($this->adapter, $operator_parser);
		return $driver->build_parser($this->adapter, null, $fragment_parser, $group_parser);
	}
	
	protected function query($type) {
		$query = $this->getMock('\PHPixie\DB\Driver\PDO\Query', array('parse'), array($this->pixie->db, null, null, null, $type));
		$query
			->expects($this->any())
			->method('parse')
			->will($this->returnCallback(function() use($query) {
				return $this->parser->parse($query);
			}));
		return $query;
	}
}