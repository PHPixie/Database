<?php

namespace PHPixie\Database\SQL\Query;

abstract class Implementation extends \PHPixie\Database\Query\Implementation implements \PHPixie\Database\SQL\Query
{
    protected $builder;
    protected $sql;

    public function __construct($database, $connection, $parser, $config, $sql)
    {
        parent::__construct($database, $connection, $parser, $config);
        $this->sql = $sql;
    }
    
    public function table($table, $alias = null)
    {
        $this->builder->table($table, $alias);
        return $this;
    }
    
    public function getTable()
    {
        return $this->builder->getTable();
    }
    
    public function execute()
    {
        $expr = $this->parse();
        $result = $this->connection->execute($expr->sql, $expr->params);
        return $result;
    }
}
