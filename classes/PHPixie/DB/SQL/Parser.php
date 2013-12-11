<?php

namespace PHPixie\DB\SQL;

abstract class Parser extends \PHPixie\DB\Parser{
	protected $db;
	protected $driver;
	protected $config;
	protected $fragment_parser;
	protected $group_parser;
	
	protected $supported_joins;
	
	public function __construct($db, $driver, $config, $fragment_parser, $group_parser) {
		parent::__construct($db, $driver, $config);
		$this->fragment_parser = $fragment_parser;
		$this->group_parser = $group_parser;
	}
	
	public function parse($query) {
		$this->query = $query;
		$expr = $this->db->expr();
		$type = $query->get_type();
		
		switch($type) {
			case 'select':
				$this->select_query($query, $expr);
				break;
			case 'insert':
				$this->insert_query($query, $expr);
				break;
			case 'update':
				$this->update_query($query, $expr);
				break;
			case 'delete':
				$this->delete_query($query, $expr);
				break;
			case 'count':
				$this->count_query($query, $expr);
				break;
			default:
				throw new \PHPixie\DB\Exception\Parser("Query type $type is not supported");
		}
		
		return $expr;
	}
	
	protected function select_query($query, $expr) {
		$expr->sql = "SELECT ";
		$this->append_fields($query, $expr);
		
		if ($this->query->get_table() !== null) {
			$expr->sql.= " FROM ";
			$this->append_table($query, $expr);
		}
		
		$this->append_joins($query, $expr);
		$this->append_conditions('where', $query->get_conditions('where'), $expr);
		$this->append_group_by($query, $expr);
		$this->append_conditions('having', $query->get_conditions('having'), $expr);
		$this->append_order_by($query, $expr);
		$this->append_limit_offset($query, $expr);
		$this->append_union($query, $expr);
	}
	
	protected function insert_query($query, $expr) {
		$expr->sql = "INSERT INTO ";
		
		$this->append_table($query, $expr, true);
		$this->append_insert_values($query, $expr);
	}
	
	protected function update_query($query, $expr) {
		$expr->sql = "UPDATE ";
		
		$this->append_table($query, $expr, true);
		$this->append_joins($query, $expr);
		$this->append_update_values($query, $expr);
		$this->append_conditions('where', $query->get_conditions('where'), $expr);
		$this->append_order_by($query, $expr);
		$this->append_limit_offset($query, $expr);
		return $expr;
	}
	
	protected function delete_query($query, $expr) {
		$expr->sql = "DELETE FROM ";
		
		$this->append_table($query, $expr, true);
		$this->append_joins($query, $expr);
		$this->append_conditions('where', $query->get_conditions('where'), $expr);
		$this->append_order_by($query, $expr);
		$this->append_limit_offset($query, $expr);
		return $expr;
	}
	
	protected function count_query($query, $expr) {
		$unions = $query->get_unions();
		$group_by = $query->get_group_by();
		
		if (!empty($unions) || !empty($group_by))
			throw new \PHPixie\DB\Exception\Parser("COUNT queries don't support GROUP BY and UNION statements. Try using them in a subquery");
			
		$expr->sql .= "SELECT COUNT (1) AS ";
		$this->fragment_parser->append_column('count', $expr);
		$expr->sql .= " FROM ";
		$this->append_table($query, $expr, true);
		$this->append_joins($query, $expr);
		$this->append_conditions('where', $query->get_conditions('where'), $expr);
	}
	
	protected function append_table($query, $expr, $required = false) {
		$table = $this->query->get_table();
		
		if ($required && $table === null) {
			$type = strtoupper($query->get_type());
			throw new \PHPixie\DB\Exception\Parser("Table not specified for $type query");
		}
		
		$this->fragment_parser->append_table($table['table'], $expr, $table['alias']);
	}
	
	protected function append_insert_values($query, $expr) {
		$data = $query->get_data();
		
		if (empty($data))
			return $this->append_empty_insert_values($query, $expr);
			
		$columns_expr = $this->db->expr();
		$values_expr  = $this->db->expr();
		
		$first = true;
		foreach($data as $column => $value) {
			if (!$first){
				$columns_expr->sql.= ', ';
				$values_expr->sql.= ', ';
			}else {
				$first = false;
			}
			$this->fragment_parser->append_column($column, $columns_expr);
			$this->fragment_parser->append_value($value, $values_expr);
		}
		$expr->sql .= "(";
		$expr->append($columns_expr);
		$expr->sql .= ") VALUES (";
		$expr->append($values_expr);
		$expr->sql .= ")";
	}
	
	protected function append_empty_insert_values($query, $expr) {
		$expr->sql.= "() VALUES()";
	}
	
	protected function append_update_values($query, $expr) {
		$expr->sql .= " SET ";
		$data = $this->query->get_data();
		
		if (empty($data))
			throw new \PHPixie\DB\Exception\Parser("Empty data passed to the UPDATE query");
			
		$first = true;
		foreach($data as $column => $value) {
			if (!$first){
				$expr->sql.= ', ';
			}else {
				$first = false;
			}
			$this->fragment_parser->append_column($column, $expr);
			$expr->sql.= " = ";
			$this->fragment_parser->append_value($value, $expr);
		}
	}
	
	protected function append_joins($query, $expr) {
		foreach($query->get_joins() as $join) {
		
			if (!isset($this->supported_joins[$join['type']]))
				throw new \PHPixie\DB\Exception\Parser("Join type '{$join['type']}' is not supported by this database driver");
				
			$expr->sql.= ' '.$this->supported_joins[$join['type']]." JOIN ";
			$this->fragment_parser->append_table($join['table'], $expr, $join['alias']);
			$this->append_conditions('on', $join['builder']->get_conditions(), $expr);
		}
	}
	
	protected function append_conditions($prefix, $conditions, $expr) {
		if(empty($conditions))
			return;
			
		$expr->sql.= ' '.strtoupper($prefix).' ';
		$expr->append($this->group_parser->parse($conditions));
	}
	
	protected function append_group_by($query, $expr) {
		$group_by = $query->get_group_by();
		
		if (empty($group_by))
			return;
		
		$expr->sql.= " GROUP BY ";
		foreach($group_by as $key => $column) {
			if ($key > 0)
				$expr->sql.= ', ';
				
			$this->fragment_parser->append_column($column, $expr);
		}
	}
	
	public function append_order_by($query, $expr) {
		$order_by = $query->get_order_by();
		
		if (empty($order_by))
			return;
		
		$expr->sql.= " ORDER BY ";
		foreach($order_by as $key => $order) {
			list($column, $dir) = $order;
			
			if ($key > 0)
				$expr->sql.= ', ';
				
			if ($dir !== 'asc' && $dir !== 'desc')
				throw new \PHPixie\DB\Exception\Parser("Order direction must be either 'asc' or  'desc'");
				
			$this->fragment_parser->append_column($column, $expr);
			$expr->sql.= ' '.strtoupper($dir);
		}
	}
	
	public function append_limit_offset($query, $expr) {
		$limit = $query->get_limit();
		$offset = $query->get_offset();
		
		if ($limit !== null)
			$expr->sql.= " LIMIT $limit";
			
		if ($offset !== null)
			$expr->sql.=" OFFSET $offset";
	}
	
	public function append_union($query, $expr) {
		foreach($query->get_unions() as $union) {
			list($subselect, $all) = $union;
			$expr->sql.= " UNION ";
			if ($all)
				$expr->sql.= "ALL ";
				
			if ($subselect instanceof \PHPixie\DB\SQL\Query) {
				$expr->append($subselect->parse());
				
			}elseif ($subselect instanceof \PHPixie\DB\SQL\Expression) {
				$expr->append($subselect);
				
			}else {
				throw new \PHPixie\DB\Exception\Parser("Union parameter must be either a Query object or SQL expression object");
			}
		}
	}
	
	protected function append_fields($query, $expr) {
		$fields = $query->get_fields();
		
		if (empty($fields)) {
			$expr->sql.= '*';
			return;
		}
		
		$first = true;
		foreach($query->get_fields() as $field) {
			if ($first) {
				$expr->sql.= ', ';
				$first = false;
			}
			if (is_array($field)) {
				$this->fragment_parser->append_column($field[0], $expr);
				$expr->sql.=" AS ".$this->fragment_parser->quote($field[1]);
			}else{
				$this->fragment_parser->append_column($field, $expr);
			}
		}
	}
	

}