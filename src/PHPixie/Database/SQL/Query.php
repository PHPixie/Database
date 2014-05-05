<?php

namespace PHPixie\Database\SQL;

abstract class Query extends \PHPixie\Database\Query
{
    protected $sql;
    protected $table;
    
    public function __construct($database, $connection, $parser, $config, $sql)
    {
        parent::__construct($database, $connection, $parser, $config);
        $this->sql = $sql;
    }

    public function table($table, $alias = null)
    {
        $this->table = array(
            'table' => $table,
            'alias' => $alias
        );

        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function batchData($columns, $rows)
    {
        $this->batchData = array(
            'columns' => $columns,
            'rows' => $rows
        );

        return $this;
    }

    public function getBatchData()
    {
        return $this->batchData;
    }

    public function data($data)
    {
        $this->batchData = null;

        return parent::data($data);
    }

    public function execute()
    {
        $expr = $this->parse();
        $result = $this->connection->execute($expr->sql, $expr->params);
        return $result;
    }
}
