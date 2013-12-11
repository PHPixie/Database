<?php

namespace PHPixie\DB\PDO;

/**
 * PDO Database implementation.
 * @package Database
 */
class Connection extends \PHPixie\DB\Connection
{

	protected $pixie;
	protected $adapter;
	
	public function __construct($pixie, $config)
	{
		parent::__construct($pixie, $config);
		
		$this->conn = new \PDO(
			$pixie->config->get("db.{$config}.connection"),
			$pixie->config->get("db.{$config}.user", ''),
			$pixie->config->get("db.{$config}.password", '')
		);
		
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$db_type = strtolower(str_replace('PDO_', '', $this->conn->getAttribute(\PDO::ATTR_DRIVER_NAME)));
		$this->adapter = $this->pixie->sql->pdo_adapter($db, $this);
	}

	public function query($type) {
		return $this->pixie->db->pdo_query('PDO', $this, $type);
	}

	public function insert_id() {
		return $this->adapter->insert_id();
	}

	public function list_columns($table) {
		return $this->adapter->list_columns($table);
	}

	public function execute($query, $params = array()){
	}

}
