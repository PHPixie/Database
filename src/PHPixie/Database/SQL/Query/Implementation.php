<?php

namespace PHPixie\Database\SQL\Query;

abstract class Implementation extends \PHPixie\Database\Query\Implementation implements \PHPixie\Database\SQL\Query
{
    protected $builder;

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
