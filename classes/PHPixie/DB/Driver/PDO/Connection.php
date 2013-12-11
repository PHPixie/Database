<?php

namespace PHPixie\DB\Driver\PDO;

class Connection extends \PHPixie\DB\Connection {
	
	protected $adapter;
	protected $adapter_name;
	protected $pdo;
	public function __construct($driver, $name, $config) {
		parent::__construct($driver, $name, $config);
		
		$options = $config->get('connection_options', array());
		if (!is_array($options))
			throw new \PHPixie\DB\Exception("PDO 'connection_options' configuration parameter must be an array");
			
		$this->pdo = $this->connect(
			$config->get('connection'),
			$config->get('user',''),
			$config->get('password', ''),
			$options
		);
		
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->adapter_name = ucfirst(strtolower(str_replace('PDO_', '', $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME))));
		$this->adapter = $driver->adapter($this->adapter_name, $config, $this);
	}

	public function insert_id() {
		return $this->adapter->insert_id();
	}

	public function list_columns($table) {
		return $this->adapter->list_columns($table);
	}

	public function execute($query, $params = array()) {
		$cursor = $this->pdo->prepare($query);
		$cursor->execute($params);
		return $this->driver->result($cursor);
	}
	
	public function pdo() {
		return $this->pdo;
	}
	
	public function adapter_name() {
		return $this->adapter_name;
	}
	
	protected function connect($connection, $user, $password, $connection_options) {
		return new \PDO($connection, $user, $password, $connection_options);
	}
}
