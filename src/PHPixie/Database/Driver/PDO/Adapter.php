<?php

namespace PHPixie\Database\Driver\PDO;

abstract class Adapter
{
    protected $config;
    protected $connection;
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
    }
    
    public function commitTransaction()
    {
        $this->connection->execute('COMMIT');
    }
    
    public function rollbackTransaction()
    {
        $this->connection->execute('ROLLBACK');
    }
    
    abstract public function listColumns($table);

}
