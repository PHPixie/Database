<?php

namespace PHPixie\DB\Driver\Mongo\Query;

class Runner {
	protected $chain = array();
	public function chain_property($property) {
		$this->chain[] = array(
			'type' => 'property',
			'name' => $property
		);
	}
	
	public function chain_method($method, $args = array()) {
		$this->chain[] = array(
			'type' => 'method',
			'name' => $method,
			'args' => $args
		);
	}
	
	public function get_chain(){
		return $this->chain;
	}
	
	public function run($connection) {
		$current = $connection->client();
		foreach($this->chain as $step) {
			switch($step['type']) {
				case 'property':
					$property = $step['name'];
					$current = $current->$property;
					break;
				case 'method':
					$args = $step['args'];
					$current = call_user_func_array(array($current, $step['name']), $args);
					
					if ($step['name'] === 'insert')
						$connection->set_insert_id($args[0]['_id']);
						
					break;
			}
		}
		
		return $current;
	}
}