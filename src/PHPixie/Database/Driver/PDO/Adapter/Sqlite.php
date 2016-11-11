<?php

namespace PHPixie\Database\Driver\PDO\Adapter;

class Sqlite extends \PHPixie\Database\Driver\PDO\Adapter
{
    public function listColumns($table)
    {
        return $this->connection->execute("PRAGMA table_info('$table')")->getField('name');
    }
    
    public function dsn($withDatabase = true)
    {
        if($withDatabase === false) {
            throw new \PHPixie\Database\Exception("SQLite does not support connections without database.");
        }
        
        $dsn = 'sqlite:'.$this->config->getRequired('file');
        return $dsn;
    }
    
    public function name()
    {
        return 'sqlite';
    }
}
