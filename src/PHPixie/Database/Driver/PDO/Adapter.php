<?php

namespace PHPixie\Database\Driver\PDO;

abstract class Adapter
{
    protected $config;
    protected $connection;
    protected $savepoint = 0;
    protected $isTransactionActive = false;
    
    public function __construct($config, $connection)
    {
        $this->config = $config;
        $this->connection = $connection;
    }

    public function insertId()
    {
        $id = $this->connection->pdo()->lastInsertId();

        if (empty($id))
            throw new \PHPixie\Database\Exception('Cannot get last insert id, probably no rows have been inserted yet.');

        return $id;
    }

    public function beginTransaction()
    {
        $this->connection->execute('BEGIN TRANSACTION');
        $this->isTransactionActive = true;
    }
    
    public function commitTransaction()
    {
        $this->connection->execute('COMMIT');
        $this->isTransactionActive = false;
    }
    
    public function rollbackTransaction($savepoint = null)
    {
        if($savepoint === null) {
            $this->connection->execute('ROLLBACK');
            $this->isTransactionActive = false;
        }else{
            $this->connection->execute('ROLLBACK TO '.$savepoint);
            $this->isTransactionActive = true;
        }
    }
    
    public function savepointTransaction($name = null)
    {
        if($name == null) {
            $this->savepoint++;
            $name = 'savepoint_'.$this->savepoint;
        }
        
        $this->connection->execute('SAVEPOINT '.$name);
        $this->isTransactionActive = true;
        return $name;
    }
    
    public function isTransactionActive()
    {
        return $this->isTransactionActive;
    }
    
    abstract public function listColumns($table);

}
