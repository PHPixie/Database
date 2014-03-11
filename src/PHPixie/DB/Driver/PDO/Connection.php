<?php

namespace PHPixie\DB\Driver\PDO;

class Connection extends \PHPixie\DB\Connection
{
    protected $adapter;
    protected $adapterName;
    protected $pdo;
    public function __construct($driver, $name, $config)
    {
        parent::__construct($driver, $name, $config);

        $options = $config->get('connectionOptions', array());
        if (!is_array($options))
            throw new \PHPixie\DB\Exception("PDO 'connectionOptions' configuration parameter must be an array");

        $this->pdo = $this->connect(
            $config->get('connection'),
            $config->get('user',''),
            $config->get('password', ''),
            $options
        );

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->adapterName = ucfirst(strtolower(str_replace('PDO_', '', $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME))));
        $this->adapter = $driver->adapter($this->adapterName, $config, $this);
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
        $cursor = $this->pdo->prepare($query);
        $cursor->execute($params);

        return $this->driver->result($cursor);
    }

    public function pdo()
    {
        return $this->pdo;
    }

    public function adapterName()
    {
        return $this->adapterName;
    }

    protected function connect($connection, $user, $password, $connectionOptions)
    {
        return new \PDO($connection, $user, $password, $connectionOptions);
    }
}
