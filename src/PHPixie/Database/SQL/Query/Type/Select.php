<?php

namespace PHPixie\Database\SQL\Query\Type;

class Select extends \PHPixie\Database\SQL\Query\Items implements \PHPixie\Database\Query\Type\Select
{
    protected $groupBy = array();
    protected $unions = array();
    
    public function fields($fields)
    {
        $this->common->fields($fields);
    }
    
    public function getFields($fields)
    {
        $this->common->getFields();
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
    
        public function getHavingBuilder()
    {
        return $this->conditionBuilder('having');
    }

    public function getHavingConditions()
    {
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
}