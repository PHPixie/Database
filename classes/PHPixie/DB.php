<?php

namespace PHPixie;

class DB {
	
	protected $pixie;
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
	
	public function condition_builder($default_operator = '=') {
		return new \PHPixie\DB\Conditions\Builder($this, $default_operator);
	}

	public function condition_group() {
		return new \PHPixie\DB\Conditions\Condition\Group();
	}
	
	public function operator($field, $operator, $value) {
		return new \PHPixie\DB\Conditions\Condition\Operator($field, $operator, $value);
	}
	
	public function expr($sql = '', $params = array()) {
		return new \PHPixie\DB\SQL\Expression($sql, $params);
	}
	
	protected function get_config($connection_name) {
		return $this->pixie->config->slice('db.'.$connection_name);
	}
	
}
