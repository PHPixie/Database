<?php

namespace PHPixie\DB\Driver\PDO;

class Result extends \PHPixie\DB\Result {
	
	protected $statement;
	protected $fetched = false;
	protected $current;
	protected $position;
	
	public function __construct($statement) {
		$this->statement = $statement;
	}
	
	public function check_fetched() {
		if (!$this->fetched)
			$this->next();
	}
	
	public function current() {
		$this->check_fetched();
		return $this->current;
	}

	public function key() {
		$this->check_fetched();
		
		if (!$this->valid())
			return null;
			
		return $this->position;
	}

	public function valid() {
		$this->check_fetched();
		return $this->current !== null;
	}
	
	public function next() {
		$this->current = $this->statement->fetchObject();
		$this->fetched = true;
		if ($this->current !== false) {
			if ($this->position === null) {
				$this->position = 0;
			}else {
				$this->position++;
			}
		}else {
			$this->current = null;
			$this->statement->closeCursor();
		}
	}
	
	public function rewind() {
		if ($this->position > 0)
			throw new \PHPixie\DB\Exception("PDO statement cannot be rewound for unbuffered queries");
	}
	
	protected function statement() {
		return $this->statement;
	}
	
}