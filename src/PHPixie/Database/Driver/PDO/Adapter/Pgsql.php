<?php

namespace PHPixie\Database\Driver\PDO\Adapter;

class Pgsql extends \PHPixie\Database\Driver\PDO\Adapter
{
    public function preparePdo($pdo)
    {
        $pdo->exec("SET NAMES 'utf8'");
    }

    public function insertId()
    {
        try {
            return $this->connection->execute('SELECT lastval() as id')->get('id');
        } catch (\Exception $e) {
            throw new \PHPixie\Database\Exception('Cannot get last insert id, probably no rows have been inserted yet.');
        }
    }

    public function listColumns($table)
    {
        return $this->connection
            ->execute("select column_name from information_schema.columns where table_name = ".
                      "'{$table}' and table_catalog = current_database()")
            ->getField('column_name');
    }
    
    public function dsn($withDatabase = true)
    {
        $dsn = 'pgsql:';
        
        $dsn.='host='.$this->config->get('host', 'localhost');
        $dsn.=';port='.$this->config->get('port', '5432');
        
        $database = 'postgres';
        
        if($withDatabase) {
            $database = $this->config->getRequired('database');
        }
        
        $dsn.=';dbname='.$database;
        
        return $dsn;
    }
    
    public function name()
    {
        return 'pgsql';
    }
}
