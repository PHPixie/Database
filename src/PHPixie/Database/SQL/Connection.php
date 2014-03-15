<?php

namespace PHPixie\Database\PDO;

/**
 * PDO Database implementation.
 * @package Database
 */
class Connection extends \PHPixie\Database\Connection
{

    protected $pixie;
    protected $adapter;

    public function __construct($pixie, $config)
    {
        parent::__construct($pixie, $config);

        $this->conn = new \PDO(
            $pixie->config->get("database..{$config}.connection"),
            $pixie->config->get("database..{$config}.user", ''),
            $pixie->config->get("database..{$config}.password", '')
        );

        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $databaseType = strtolower(str_replace('PDO_', '', $this->conn->getAttribute(\PDO::ATTR_DRIVER_NAME)));
        $this->adapter = $this->pixie->sql->pdoAdapter($database, $this);
    }

    public function query($type)
    {
        return $this->pixie->database->pdoQuery('PDO', $this, $type);
    }

    public function insertId()
    {
        return $this->adapter->insertId();
    }

    public function listColumns($table)
    {
        return $this->adapter->listColumns($table);
    }

    public function execute($query, $params = array())
    {
    }

}
