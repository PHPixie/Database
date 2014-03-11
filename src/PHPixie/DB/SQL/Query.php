<?php

namespace PHPixie\DB\SQL;

abstract class Query extends \PHPixie\DB\Query
{
    protected $table;
    protected $groupBy = array();
    protected $joins = array();
    protected $unions = array();
    protected $bulkData;

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

    public function bulkData($columns, $rows)
    {
        $this->bulkData = array(
            'columns' => $columns,
            'rows' => $rows
        );
        
        return $this;
    }
    
    public function getBulkData()
    {
        return $this->bulkData;
    }
    
    public function data($data) 
    {
        $this->bulkData = null;
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

    public function join($table, $alias = null, $type = 'inner')
    {
        $this->joins[] = array(
            'builder' => $this->conditions->builder('=*'),
            'table' => $table,
            'alias' => $alias,
            'type'  => $type
        );

        return $this;
    }

    public function getJoins()
    {
        return $this->joins;
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

    protected function lastOnBuilder()
    {
        if (empty($this->joins))
            throw new \PHPixie\DB\Exception\Builder("Cannot add join conditions as no joins have been added to the query.");

        $join = end($this->joins);

        $this->lastUsedBuilder = $join['builder'];

        return $this->lastUsedBuilder;
    }

    protected function addOnCondition($args, $logic = 'and', $negate = false)
    {
        $this->lastUsedBuilder = $this->lastOnBuilder();
        $this->lastUsedBuilder->addCondition($logic, $negate, $args);

        return $this;
    }

    public function getHavingBuilder() {
        return $this->conditionBuilder('having');
    }
    
    public function getHavingConditions() {
        return $this->getConditions('having');
    }
    
    public function having()
    {
        return $this->addCondition(func_get_args(), 'and', false, 'having');
    }

    public function orHaving()
    {
        return $this->addCondition(func_get_args(), 'or', false, 'having');
    }

    public function xorHaving()
    {
        return $this->addCondition(func_get_args(), 'xor', false, 'having');
    }

    public function havingNot()
    {
        return $this->addCondition(func_get_args(), 'and', true, 'having');
    }

    public function orHavingNot()
    {
        return $this->addCondition(func_get_args(), 'or', true, 'having');
    }

    public function xorHavingNot()
    {
        return $this->addCondition(func_get_args(), 'xor', true, 'having');
    }

    public function startHavingGroup($logic = 'and')
    {
        return $this->startConditionGroup($logic, 'having');
    }

    public function endHavingGroup()
    {
        return $this->endConditionGroup('having');
    }

    public function on()
    {
        return $this->addOnCondition(func_get_args(), 'and', false);
    }

    public function orOn()
    {
        return $this->addOnCondition(func_get_args(), 'or', false);
    }

    public function xorOn()
    {
        return $this->addOnCondition(func_get_args(), 'xor', false);
    }

    public function onNot()
    {
        return $this->addOnCondition(func_get_args(), 'and', true);
    }

    public function orOnNot()
    {
        return $this->addOnCondition(func_get_args(), 'or', true);
    }

    public function xorOnNot()
    {
        return $this->addOnCondition(func_get_args(), 'xor', true);
    }

    public function startOnGroup($logic = 'and')
    {
        $this->lastOnBuilder()->startGroup($logic);

        return $this;
    }

    public function endOnGroup()
    {
        $this->lastOnBuilder()->endGroup();

        return $this;
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
