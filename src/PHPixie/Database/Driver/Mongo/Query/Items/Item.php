<?php

namespace PHPixie\Database\Driver\Mongo\Query\Items;

abstract class Item extends \PHPixie\Database\Driver\Mongo\Query\Implementation
{
    protected function addCondition($args, $logic = 'and', $negate = false, $builderName = null)
    {
        $this->common->addCondition($args, $logic, $negate, $builderName);
        return $this;
    }

    protected function startConditionGroup($logic = 'and', $builderName = null)
    {
        $this->common->startConditionGroup($logic, $builderName);
        return $this;
    }

    protected function endConditionGroup($builderName = null)
    {
        $this->common->endGroup($builderName);
        return $this;
    }

    public function getWhereBuilder()
    {
        return $this->common->conditionBuilder('where');
    }

    public function getWhereConditions()
    {
        return $this->common->getConditions('where');
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
        $this->common->startGroup('and', false);
    }

    public function startOrGroup()
    {
        $this->common->startGroup('or', false);
    }

    public function startXorGroup()
    {
        $this->common->startGroup('xor', false);
    }

    public function startNotGroup()
    {
        $this->common->startGroup('and', true);
    }

    public function startOrNotGroup()
    {
        $this->common->startGroup('or', true);
    }

    public function startXorNotGroup()
    {
        $this->common->startGroup('xor', true);
    }

    public function endGroup()
    {
        $this->common->endGroup();
    }
}