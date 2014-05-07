<?php

namespace PHPixie\Driver\PDO\Query\Type;

class Select extends \PHPixie\Driver\PDO\Query\Items\Implementation implements \PHPixie\Driver\SQL\Query\Type\Select
{
    public function fields($fields)
    {
        $this->builder->fields($fields);
    }
    
    public function getFields($fields)
    {
        $this->builder->getFields();
    }

    public function groupBy($field)
    {
        return $this->builder->groupBy($field);
        return $this;
    }

    public function getGroupBy()
    {
        return $this->builder->getGroupBy();
    }
    
    public function union($query, $all=false)
    {
        return $this->builder->union($query, $all);
        return $this;
    }
    
    public function getUnions()
    {
        return $this->builder->getUnions();
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