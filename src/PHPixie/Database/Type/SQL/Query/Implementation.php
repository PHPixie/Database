<?php

namespace PHPixie\Database\Type\SQL\Query;

abstract class Implementation extends \PHPixie\Database\Query\Implementation implements \PHPixie\Database\Type\SQL\Query
{
    protected $builder;

    public function table($table, $alias = null)
    {
        $this->builder->setTable($table, $alias);

        return $this;
    }

    public function clearTable()
    {
        $this->builder->clearValue('table');

        return $this;
    }

    public function getTable()
    {
        return $this->builder->getValue('table');
    }

    public function parse()
    {
        return $this->parser->parse($this);
    }

    public function execute()
    {
        $expr = $this->parse();
        $result = $this->connection->execute($expr->sql, $expr->params);

        return $result;
    }
}
