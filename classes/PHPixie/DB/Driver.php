<?php

namespace PHPixie\DB;

abstract class Driver {
	
	protected $db;
	protected $parsers =  array();
	protected $connections = array();
	
	public function __construct($db) {
		$this->db = $db;
	}
	
	public function parser($connection_name) {
		if (!isset($this->parsers[$connection_name]))
			$this->parsers[$connection_name] = $this->build_parser_instance($connection_name);
			
		return $this->parsers[$connection_name];
	}
	
	public function query($type = 'select', $connection_name = 'default') {
		$connection = $this->db->get($connection_name);
		$config     = $connection->config();
		$parser     = $this->parser($connection_name);
		return $this->build_query($connection, $parser, $config, $type);
	}
	
	public abstract function build_connection($name, $config);
	public abstract function build_parser_instance($connection_name);
	public abstract function build_query($connection, $parser, $config, $type);
	public abstract function result($cursor);
	
}