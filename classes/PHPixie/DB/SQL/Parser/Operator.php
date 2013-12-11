<?php

namespace PHPixie\DB\SQL\Parser;

abstract class Operator extends \PHPixie\DB\Parser\Operator {

	protected $db;
	protected $fragment_parser;
	
	protected $operators = array(
		'compare' => array('<', '<=', '=', '<>', '!=', '>=', '>', '<*', '<=*', '=*', '<>*', '!=*', '>=*', '>*'),
		'pattern' => array('like', 'not like', 'regexp', 'not regexp'),
		'in'      => array('in', 'not in'),
		'between' => array('between', 'not between'),
	);
	
	public function __construct($db, $fragment_parser) {
		$this->db = $db;
		$this->fragment_parser = $fragment_parser;
		parent::__construct();
	}
	
	protected function prefix($field, $operator) {
		$expr = $this->db->expr();
		$this->fragment_parser->append_column($field, $expr);
		$expr->sql .= ' '.strtoupper($operator).' ';
		return $expr;
	}
	
	protected function single_value($values, $operator) {
		if(count($values) !== 1) 
			throw new \PHPixie\DB\Exception\Parser(strtoupper($operator)." operator requires a single parameter");
			
		return $values[0];
	}
	
	protected function parse_compare($field, $operator, $values) {
		$is_column =  false;
		
		if (substr($operator, -1, 1) === '*') {
			$operator = substr($operator, 0, -1);
			$is_column = true;
		}
		
		$value = $this->single_value($values, $operator);
		
		if ($operator === '!=')
			$operator = '<>';
		
		if ($value === null) {
			if ($is_column)
				throw new \PHPixie\DB\Exception\Parser("A column comparison operator '{$operator}*' was given a NULL instead of column");
				
			if ($operator === '=') {
				$operator = 'is';
			}elseif($operator === '<>') {
				$operator = 'is not';
			}
		}
		
		$expr = $this->prefix($field, $operator);
		
		if ($value === null) {
			$expr->sql.= "NULL";
		}elseif($is_column) {
			$this->fragment_parser->append_column($value, $expr);
		}else{
			$this->fragment_parser->append_value($value, $expr);
		}
		
		return $expr;
	}
	
	protected function parse_between($field, $operator, $range) {
		if (count($range) !== 2) 
			throw new \PHPixie\DB\Exception\Parser(strtoupper($operator)." operator parameter requires two parameters");
			
		$expr = $this->prefix($field, $operator);
		$this->fragment_parser->append_value($range[0], $expr);
		$expr->sql.= " AND ";
		$this->fragment_parser->append_value($range[1], $expr);
		return $expr;
	}
	
	protected function parse_pattern($field, $operator, $values) {
		$value = $this->single_value($values, $operator);
		
		$expr = $this->prefix($field, $operator);
		$this->fragment_parser->append_value($value, $expr);
		return $expr;
	}
	
	protected function parse_in($field, $operator, $values) {
		$value = $this->single_value($values, $operator);
		
		$expr = $this->prefix($field, $operator);
		if (is_array($value)) {
			$list_sql = str_pad('', count($value) * 3 - 2, '?, ');
			$expr->sql.= "($list_sql)";
			$expr->params = array_merge($expr->params, $value);
		}elseif($value instanceof \PHPixie\DB\SQL\Query) {
			$subquery = $value-> parse();
			$expr->sql.= "( ";
			$expr->append($subquery);
			$expr->sql.= " )";
		}elseif ($value instanceof \PHPixie\DB\SQL\Expression) {
			$expr->sql.= "( ";
			$expr->append($value);
			$expr->sql.= " )";
		}else {
			throw new \PHPixie\DB\Exception\Parser(strtoupper($operator)." operator parameter must be either an array, a query or an expression");
		}
		
		return $expr;
	}
}
