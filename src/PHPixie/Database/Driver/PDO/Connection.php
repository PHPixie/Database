<?php

namespace PHPixie\Database\Driver\PDO;

class Connection extends \PHPixie\Database\Type\SQL\Connection
{
    protected $adapter;
    protected $adapterName;
    protected $pdo;
    
    public function insertId()
    {
        return $this->adapter->insertId();
    }
    
    public function connect()
    {
        $config = $this->config;
        
        $options = $config->get('connectionOptions', array());
        if (!is_array($options))
            throw new \PHPixie\Database\Exception("PDO 'connectionOptions' configuration parameter must be an array");

        $this->pdo = $this->buildPdo(
            $config->get('connection'),
            $config->get('user',''),
            $config->get('password', ''),
            $options
        );

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->adapterName = strtolower(str_replace('PDO_', '', $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME)));
        $this->adapter = $this->driver->adapter($this->adapterName, $config, $this);
    }
    
    public function disconnect()
    {
        $this->adapter = null;
        $this->pdo = null;
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

    protected function buildPdo($connection, $user, $password, $connectionOptions)
    {
        return new \PDO($connection, $user, $password, $connectionOptions);
    }
    
    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }
    
    public function commitTransaction()
    {
        $this->pdo->commit();
    }
    
    public function rollbackTransaction()
    {
        $this->pdo->rollBack();
    }
    
    public function inTransaction()
    {
        return $this->pdo->inTransaction();
    }
    
    public function rollbackTransactionTo($savepoint)
    {
        $this->adapter->rollbackTransactionTo($savepoint);
    }
    
    protected function createTransactionSavepoint($name)
    {
        $this->adapter->createTransactionSavepoint($name);
    }
    

}
