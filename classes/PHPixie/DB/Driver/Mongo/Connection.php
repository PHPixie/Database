<?php

namespace PHPixie\DB\Driver\Mongo;

class Connection extends \PHPixie\DB\Connection {

	protected $client;
	protected $insert_id;
	
	public function __construct($driver, $name, $config) {
		parent::__construct($driver, $name, $config);
		
		$options = $config->get('connection_options', array());
		if (!is_array($options))
			throw new \PHPixie\DB\Exception("Mongo 'connection_options' configuration parameter must be an array");
			
		$options['username'] = $config->get('user', '');
		$options['password'] = $config->get('password', '');
		
		$this->client = $this->connect($config->get('connection'), $options);
	}

	public function insert_id() {
		return $this->last_insert_id;
	}

	public function run($runner){
		$result = $runner->run($this);
		if ($result instanceof \MongoCursor)
			return $this->driver->result($result);
		return $result;
	}
	
	public function set_insert_id($id) {
		$this->last_insert_id = $id;
	}
	
	protected function connect($connection, $options){
		return new \MongoClient($connection, $options);
	}
	
	public function client() {
		return $this->client;
	}
}
