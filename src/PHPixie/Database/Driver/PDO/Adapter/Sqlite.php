<?php

namespace PHPixie\Database\Driver\PDO\Adapter;

class Sqlite extends \PHPixie\Database\Driver\PDO\Adapter
{
    public function listColumns($table)
    {
        return $this->connection->execute("PRAGMA table_info('$table')")->getField('name');
    }
}
