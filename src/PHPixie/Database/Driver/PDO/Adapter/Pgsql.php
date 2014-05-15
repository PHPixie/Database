<?php

namespace PHPixie\Database\Driver\PDO\Adapter;

class Pgsql extends \PHPixie\Database\Driver\PDO\Adapter
{
    public function __construct($config, $connection)
    {
        parent::__construct($config, $connection);
        $this->connection->execute("SET NAMES utf8");
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
                            ->execute("select column_name from information_schema.columns where table_name = '{$table}' and table_catalog = current_database()")
                            ->getField('column_name');
    }
}
