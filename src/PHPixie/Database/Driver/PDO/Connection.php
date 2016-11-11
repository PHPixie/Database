<?php

namespace PHPixie\Database\Driver\PDO;

class Connection extends \PHPixie\Database\Type\SQL\Connection
{
    protected $adapter;
    protected $pdo;
    
    public function __construct($driver, $name, $config)
    {
        parent::__construct($driver, $name, $config);
        $dsn = $config->get('connection');
        
        if($dsn !== null) {
            $parts = explode(':', $dsn, 2);
            $adapterName = $parts[0];
        } else {
            $adapterName = $config->getRequired('adapter');
        }
        
        $this->adapter = $driver->adapter($adapterName, $config, $this);
    }
    
    public function buildPdo($withDatabase = true)
    {
        $config = $this->config;
        
        $dsn = $config->get('connection');
        
        if(!$withDatabase || $dsn === null) {
            $dsn = $this->adapter->dsn($withDatabase);
        }
        
        $options = $config->get('connectionOptions', array());
        if (!is_array($options)) {
            throw new \PHPixie\Database\Exception("PDO 'connectionOptions' configuration parameter must be an array");
        }
        
        $pdo = $this->buildPdoInstance(
            $dsn,
            $config->get('user',''),
            $config->get('password', ''),
            $options
        );
        
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->adapter->preparePdo($pdo);
        
        return $pdo;
    }
    
    public function insertId()
    {
        return $this->adapter->insertId();
    }
    
    public function disconnect()
    {
        $this->pdo = null;
    }

    public function listColumns($table)
    {
        return $this->adapter->listColumns($table);
    }

    public function execute($query, $params = array())
    {
        $cursor = $this->pdo()->prepare($query);
        $cursor->execute($params);

        return $this->driver->result($cursor);
    }

    public function pdo()
    {
        if($this->pdo === null) {
            $this->pdo = $this->buildPdo();
        }
        
        return $this->pdo;
    }
    
    public function adapterName()
    {
        return $this->adapter->name();
    }

    protected function buildPdoInstance($connection, $user, $password, $connectionOptions)
    {
        return new \PDO($connection, $user, $password, $connectionOptions);
    }
    
    public function beginTransaction()
    {
        $this->pdo()->beginTransaction();
    }
    
    public function commitTransaction()
    {
        $this->pdo()->commit();
    }
    
    public function rollbackTransaction()
    {
        $this->pdo()->rollBack();
    }
    
    public function inTransaction()
    {
        return $this->pdo()->inTransaction();
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
