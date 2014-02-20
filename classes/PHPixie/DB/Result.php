<?php

namespace PHPixie\DB;

abstract class Result implements \Iterator {


	public function as_array() {
		$this->rewind();
		$arr = array();
		foreach ($this as $row)
			$arr[] = $row;
		return $arr;
	}

	public function get($column) {
		if (!$this->valid())
			return;
			
		$current = $this->current();
		if (isset($current->$column))
			return $current->$column;
	}
	
	public function get_column($column = null, $skip_nulls = false) {
		$this->rewind();
		$values = array();
		foreach($this as $row)
			if ($column === null)
				$column = key(get_object_vars($row));
			if (isset($row->$column)) {
				$values[] = $row->$column;
			}elseif(!$skip_nulls)
				$values[] = null;
		return $values;
	}
	
	abstract function current();
	abstract function next();
	abstract function valid();
	abstract function rewind();
}