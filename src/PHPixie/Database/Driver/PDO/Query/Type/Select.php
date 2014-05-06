<?php

namespace PHPixie\Driver\PDO\Query\Type;

class Select extends \PHPixie\Driver\PDO\Query\Type implements \PHPixie\Driver\SQL\Query\Type\Select
{
    public function fields($fields)
    {
        $this->common->fields($fields);
    }
    
    public function getFields($fields)
    {
        $this->common->getFields();
    }
        

    
    public function groupBy($field)
    {
        return $this->common->groupBy($field);
        return $this;
    }

    public function getGroupBy()
    {
        return $this->common->getGroupBy();
    }
    
    public function union($query, $all=false)
    {
        return $this->common->union($query, $all);
        return $this;
    }
    
    public function getUnions()
    {
        return $this->common->getUnions();
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