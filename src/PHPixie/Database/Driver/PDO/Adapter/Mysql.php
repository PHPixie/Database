<?php

namespace PHPixie\Database\Driver\PDO\Adapter;

class Mysql extends \PHPixie\Database\Driver\PDO\Adapter
{
    public function preparePdo($pdo)
    {
        $pdo->exec("SET NAMES " . $this->config->get('charset', 'utf8'));
    }

    public function listColumns($table)
    {
        return $this->connection->execute("DESCRIBE `$table`")->getField('Field');
    }
    
    public function dsn($withDatabase = true)
    {
        $dsn = 'mysql:';
        if($socket = $this->config->get('unixSocket')) {
            $dsn.='unix_socket='.$socket;
        } else {
            $dsn.='host='.$this->config->get('host', 'localhost');
            $dsn.=';port='.$this->config->get('port', '3306');
        }
        
        if($charset = $this->config->get('charset')) {
            $dsn.=';charset='.$charset;
        }
        
        if($withDatabase) {
            $dsn.=';dbname='.$this->config->getRequired('database');
        }
        
        return $dsn;
    }
    
    public function name()
    {
        return 'mysql';
    }
}
