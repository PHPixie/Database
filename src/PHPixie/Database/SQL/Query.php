<?php

namespace PHPixie\Database\SQL;

abstract class Query extends \PHPixie\Database\Query
{
    protected $table;
    protected $groupBy = array();
    protected $joins = array();
    protected $unions = array();
    protected $batchData;

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

    public function groupBy($field = null)
    {
        $this->groupBy[] = $field;

        return $this;
    }

    public function getGroupBy()
    {
        return $this->groupBy;
    }



    public function union($query, $all=false)
    {
        $this->unions[] = array($query, $all);

        return $this;
    }

    public function getUnions()
    {
        return $this->unions;
    }


    public function execute()
    {
        $expr = $this->parse();
        $result = $this->connection->execute($expr->sql, $expr->params);
        if ($this->getType() === 'count')
            return $result->get('count');
        return $result;
    }
}
