<?php

namespace PHPixie;

class DB {
	
	protected $pixie;
	protected $conditions;
	
	protected $drivers = array();
	protected $connections =  array();
	
	public function __construct($pixie) {
		$this->pixie = $pixie;
	}
	
	public function get($connection_name = 'default') {
		if (!isset($this->connections[$connection_name])) {
			$config = $this->get_config($connection_name);
			$driver = $this->driver($config->get('driver'));
			$this->connections[$connection_name] = $driver->build_connection($connection_name, $config);
		}
		return $this->connections[$connection_name];
	}
	
	public function driver($name) {
		if (!isset($this->drivers[$name]))
			$this->drivers[$name] = $this->build_driver($name);
			
		return $this->drivers[$name];
	}
	
	public function build_driver($name) {
		$class = '\PHPixie\DB\Driver\\'.$name;
		return new $class($this);
	}
	
	public function query($type = 'select', $connection_name = 'default') {
		return $this->get($connection_name)->query($type);
	}
	
	public function conditions() {
		if ($this->conditions === null)
			$this->conditions = $this->build_conditions();
		return $this->conditions;
	}
	
	protected function get_conditions() {
		return new \PHPixie\DB\Conditions();
	}
	
	public function condition_placeholder() {
		return new \PHPixie\DB\Conditions\Condition\Placeholder();
	}
	
	public function expr($sql = '', $params = array()) {
		return new \PHPixie\DB\SQL\Expression($sql, $params);
	}
	
	protected function get_config($connection_name) {
		return $this->pixie->config->slice('db.'.$connection_name);
	}
	
}
