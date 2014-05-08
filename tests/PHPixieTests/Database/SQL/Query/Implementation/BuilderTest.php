<?php

namespace PHPixieTests\SQL\Query\Implementation;

/**
 * @coversDefaultClass \PHPixie\Database\SQL\Query\Implementation\Builder
 */
class BuilderTest extends \PHPixieTests\Query\Implementation\BuilderTest
{
    
    protected $builderClass = '\PHPixie\Database\SQL\Query\Implementation\Builder';
    
    //doooooooooo
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
    
    public function valuesData($data){
        $this->data = $this->driver->valuesData($data);    
    }
    
    public function batchData($rows, $columns){
        $this->data = $this->driver->batchData($data);    
    }
    
    public function join($table, $alias, $type)
    {
        $this->joins[] = array(
            'builder' => $this->builder->conditionBuilder('=*'),
            'table' => $table,
            'alias' => $alias,
            'type'  => $type
        );
    }

    public function getJoins()
    {
        return $this->joins;
    }
    
    protected function lastOnBuilder()
    {
        if (empty($this->joins))
            throw new \PHPixie\Database\Exception\Builder("Cannot add join conditions as no joins have been added to the query.");

        $join = end($this->joins);

        $builder = $join['builder'];
        $this->defaultBuilder = $builder;

        return $builder;
    }

    protected function addOnCondition($args, $logic, $negate)
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
    
    public function groupBy($field)
    {
        $this->groupBy[] = $field;
    }

    public function getGroupBy()
    {
        return $this->groupBy;
    }
    
    public function union($query)
    {
        $this->unions[] = array($query, $all);
    }
    
    public function getUnions()
    {
        return $this->unions;
    }
    
    
    
    protected function getDriver()
    {
        return $this->quickMock('\PHPixie\Database\SQL\Driver', array('valuesData'));
    }
}