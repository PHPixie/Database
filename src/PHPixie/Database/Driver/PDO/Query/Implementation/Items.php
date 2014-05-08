<?php

namespace PHPixie\Database\Driver\PDO\Query\Implementation;

abstract class Items extends \PHPixie\Database\Driver\PDO\Query\Implementation implements \PHPixie\Database\SQL\Query\Items
{

    public function limit($limit)
    {
        $this->builder->limit($limit);
        return $this;
    }

    public function getLimit()
    {
        return $this->builder->getLimit();
    }

    public function offset($offset)
    {
        $this->builder->offset($offset);
        return $this;
    }

    public function getOffset()
    {
        return $this->builder->getOffset();
    }

    public function orderAscendingBy($field)
    {
		$this->builder->orderAscendingBy($field);
        return $this;
    }

    public function orderDescendingBy($field)
    {
		$this->builder->orderDescendingBy($field);
        return $this;
    }
    
    public function getOrderBy()
    {
        return $this->builder->getOrderBy();
    }

    public function join($table, $alias = null, $type = 'inner')
    {
        return $this->builder->join($table, $alias, $type);
    }

    public function getJoins()
    {
        return $this->builder->getJoins();
    }
    
    protected function addCondition($args, $logic = 'and', $negate = false, $builderName = null)
    {
        $this->builder->addCondition($args, $logic, $negate, $builderName);
        return $this;
    }

    protected function startConditionGroup($logic = 'and', $builderName = null)
    {
        $this->builder->startConditionGroup($logic, $builderName);
        return $this;
    }

    protected function endConditionGroup($builderName = null)
    {
        $this->builder->endGroup($builderName);
        return $this;
    }

    public function getWhereBuilder()
    {
        return $this->builder->conditionBuilder('where');
    }

    public function getWhereConditions()
    {
        return $this->builder->getConditions('where');
    }

    public function where()
    {
        return $this->addCondition(func_get_args(), 'and', false, 'where');
    }

    public function orWhere()
    {
        return $this->addCondition(func_get_args(), 'or', false, 'where');
    }

    public function xorWhere()
    {
        return $this->addCondition(func_get_args(), 'xor', false, 'where');
    }

    public function whereNot()
    {
        return $this->addCondition(func_get_args(), 'and', true, 'where');
    }

    public function orWhereNot()
    {
        return $this->addCondition(func_get_args(), 'or', true, 'where');
    }

    public function xorWhereNot()
    {
        return $this->addCondition(func_get_args(), 'xor', true, 'where');
    }

    public function startWhereGroup()
    {
        return $this->startConditionGroup('and', false, 'where');
    }

    public function startOrWhereGroup()
    {
        return $this->startConditionGroup('or', false, 'where');
    }

    public function startXorWhereGroup()
    {
        return $this->startConditionGroup('xor', false, 'where');
    }

    public function startWhereNotGroup()
    {
        return $this->startConditionGroup('and', true, 'where');
    }

    public function startOrWhereNotGroup()
    {
        return $this->startConditionGroup('or', true, 'where');
    }

    public function startXorWhereNotGroup()
    {
        return $this->startConditionGroup('xor', true, 'where');
    }


    public function endWhereGroup()
    {
        return $this->endConditionGroup('where');
    }

    public function _and()
    {
        return $this->addCondition(func_get_args(), 'and', false);
    }

    public function _or()
    {
        return $this->addCondition(func_get_args(), 'or', false);
    }

    public function _xor()
    {
        return $this->addCondition(func_get_args(), 'xor', false);
    }

    public function _andNot()
    {
        return $this->addCondition(func_get_args(), 'and', true);
    }

    public function _orNot()
    {
        return $this->addCondition(func_get_args(), 'or', true);
    }

    public function _xorNot()
    {
        return $this->addCondition(func_get_args(), 'xor', true);
    }

    public function startGroup()
    {
        $this->builder->startGroup('and', false);
    }

    public function startOrGroup()
    {
        $this->builder->startGroup('or', false);
    }

    public function startXorGroup()
    {
        $this->builder->startGroup('xor', false);
    }

    public function startNotGroup()
    {
        $this->builder->startGroup('and', true);
    }

    public function startOrNotGroup()
    {
        $this->builder->startGroup('or', true);
    }

    public function startXorNotGroup()
    {
        $this->builder->startGroup('xor', true);
    }


    public function endGroup()
    {
        $this->builder->endGroup();
    }
    
    protected function addOnCondition($args, $logic = 'and', $negate = false)
    {
        $this->builder->addOnCondition($args, $logic, $negate);
        return $this;
    }
    
    public function startOnConditionGroup($logic = 'and', $negate = false)
    {
        $this->builder->startOnConditionGroup($logic, $negate);
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

    public function startOnGroup()
    {
        return $this->startOnConditionGroup('and', false);
    }

    public function startOrOnGroup()
    {
        return $this->startOnConditionGroup('or', false);
    }

    public function startXorOnGroup()
    {
        return $this->startOnConditionGroup('xor', false);
    }

    public function startOrNotGroup()
    {
        return $this->startOnConditionGroup('and', true);
    }

    public function startOrOnNotGroup()
    {
        return $this->startOnConditionGroup('or', true);
    }

    public function startXorOnNotGroup()
    {
        return $this->startOnConditionGroup('xor', true);
    }

    public function endOnGroup()
    {
        $this->lastOnBuilder()->endGroup();
        return $this;
    }
}
