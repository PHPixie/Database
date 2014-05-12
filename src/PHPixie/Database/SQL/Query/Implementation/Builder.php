<?php

namespace PHPixie\Database\SQL\Query\Implementation;

class Builder extends \PHPixie\Database\Query\Implementation\Builder{

    protected $joins = array();

    public function setTable($table, $alias)
    {
        $this->setValue('table', array(
            'table' => $table,
            'alias' => $alias
        ));
    }
    
    public function addJoin($table, $alias, $type)
    {
        $this->addToArray('joins', array(
            'builder' => $this->builder->conditionBuilder('=*'),
            'table' => $table,
            'alias' => $alias,
            'type'  => $type
        ));
    }

    public function addGroupBy($args)
    {
        $this->builder->addValuesToArray('groupBy', $args);
    }

    public function addUnion($query, $all)
    {
        $this->builder->addToArray('unions', array('query' => $query, 'all' => $all));
    }

    public function addIncrement($args)
    {
        $this->builder->addKeyValuesToArray('increment', $args, true);
    }
    
    public function setBatchData($columns, $rows)
    {
        $this->setValue('batchData', array('columns' => $columns, 'rows' => $rows));
    }
    
    protected function lastOnBuilder()
    {
        $this->assert(!empty($this->joins), "Cannot add join conditions as no joins have been added to the query.");
        $join = end($this->joins);
        $builder = $join['builder'];
        $this->defaultBuilder = $builder;

        return $builder;
    }

    public function addOnCondition($args, $logic, $negate)
    {
        $this->lastOnBuilder()->addCondition($logic, $negate, $args);
    }
    
    public function startOnConditionGroup($logic, $negate)
    {
        $this->lastOnBuilder()->startConditionGroup($logic, $negate);
    }
    
    public function endOnConditionGroup()
    {
        $this->lastOnBuilder()->startConditionGroup();
    }
    
}