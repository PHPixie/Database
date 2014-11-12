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

    public function rollbackTransactionTo($savepoint)
    {
        $this->connection->execute('ROLLBACK TO '.$savepoint);
    }
    
    public function createTransactionSavepoint($name)
    {
        $this->connection->execute('SAVEPOINT '.$name);
    }
    
    abstract public function listColumns($table);

}
