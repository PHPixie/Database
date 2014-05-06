<?php

namespace PHPixie\Database\SQL\Query\Items;

class Common extends \PHPixie\Database\Query\Implementation\Items\Common{

    protected $sql;
    protected $joins = array();
    protected $groupBy = array();
    protected $unions = array();

    public function __construct($conditions, $sql)
    {
        parent::__construct($conditions);
        $this->sql = $sql;
    }
    
    public function valuesData($data){
        $this->data = $this->sql->valuesData($data);    
    }
    
    public function bulkData($rows, $columns){
        $this->data = $this->sql->bulkData($data);    
    }
    
    public function join($table, $alias, $type)
    {
        $this->joins[] = array(
            'builder' => $this->common->conditionBuilder('=*'),
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
    
}