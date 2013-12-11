<?php

namespace PHPixie\DB;

abstract class Parser {
	protected $db;
	protected $driver;
	protected $conig;
	
	public function __construct($db, $driver, $config){
		$this->db  = $db;
		$this->driver = $driver;
		$this->config = $config;
	}
	
	public abstract function parse($query);
	
}