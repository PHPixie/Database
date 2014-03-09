<?php

namespace PHPixie\DB\Driver\PDO\Adapter;

class Sqlite extends \PHPixie\DB\Driver\PDO\Adapter
{
    public function listColumns($table)
    {
        return $this->connection->execute("PRAGMA table_info('$table')")->getColumn('name');
    }
}
