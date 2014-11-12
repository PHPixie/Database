<?php

namespace PHPixie\Database\Driver\PDO\Adapter;

class Mysql extends \PHPixie\Database\Driver\PDO\Adapter
{
    public function __construct($config, $connection)
    {
        parent::__construct($config, $connection);
        $this->connection->execute("SET NAMES utf8");
    }

    public function listColumns($table)
    {
        return $this->connection->execute("DESCRIBE `$table`")->getField('Field');
    }
}
