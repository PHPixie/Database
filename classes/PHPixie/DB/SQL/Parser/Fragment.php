<?php

namespace PHPixie\DB\SQL\Parser;

abstract class Fragment {
	protected $quote;
	
	
	public function quote($str) {
		return $this->quote.$str.$this->quote;
	}
	
	public function append_column($column, $expr) {
		if (is_object($column) && $column instanceof \PHPixie\DB\SQL\Expression)
			return $expr->append($column);
		
		if(strpos($column, '.')){
			$column = explode('.', $column);
			$expr->sql.= $this->quote($column[0]).'.';
			$column = $column[1];
		}
		
		if ($column !== '*')
			$column = $this->quote($column);
		
		$expr->sql.= $column;
		
		return $expr;
	}
	
	public function append_table($table, $expr, $alias = null) {
		
		if (is_string($table)) {
			$expr->sql.= $this->quote($table);
			
		}elseif( ($is_query = $table instanceof \PHPixie\DB\SQL\Query) || $table instanceof \PHPixie\DB\SQL\Expression) {
		
			if ($is_query)
				$table = $table->parse();
			
			$expr->sql.= "( ";
			$expr->append($table);
			$expr->sql.= " )";
		}else {
			throw new \PHPixie\DB\Exception\Parser("Parameter type ".get_class($table)." cannot be used as a table");
		}
		
		if ($alias !== null)
			$expr->sql.= " AS ".$this->quote($alias);
			
		return $expr;
	}
	
	public function append_value($value, $expr) {
		if ($value instanceof \PHPixie\DB\SQL\Expression) {
			$expr->append($value);
		}elseif ($value instanceof \PHPixie\DB\SQl\Query) {
			$expr->sql.= "( ";
			$expr->append($value-> parse());
			$expr->sql.= " )";
		}else {
			$expr->sql.= '?';
			$expr->params[]= $value;
		}
		
		return $expr;
	}
}