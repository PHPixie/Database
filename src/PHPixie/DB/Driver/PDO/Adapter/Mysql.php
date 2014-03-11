<?php

namespace PHPixie\DB\Driver\PDO\Adapter;

class Mysql extends \PHPixie\DB\Driver\PDO\Adapter
{
    public function __construct($config, $connection)
    {
        parent::__construct($config, $connection);
        $this->connection->execute("SET NAMES utf8");
    }

    public function listColumns($table)
    {
        return $this->connection->execute("DESCRIBE `$table`")->getColumn('Field');
    }
}
