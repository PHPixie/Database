<?php

namespace PHPixie\Database\Driver\Mongo\Query;

abstract class Item extends \PHPixie\Database\Driver\Mongo\Query
{
    public function __construct($connection, $parser, $builder)
    {
        parent::__construct($connection, $parser, $builder);

        $this->aliases = array_merge($this->aliases, array(
            'and' => '_and',
            'or'  => '_or',
            'xor' => '_xor',
            'not' => '_not',
        ));
    }

    protected function addCondition($args, $logic = 'and', $negate = false, $builderName = null)
    {
        $this->builder->addCondition($args, $logic, $negate, $builderName);

        return $this;
    }

    protected function startConditionGroup($logic = 'and', $negate = false, $builderName = null)
    {
        $this->builder->startConditionGroup($logic, $negate, $builderName);

        return $this;
    }

    protected function endConditionGroup($builderName = null)
    {
        $this->builder->endConditionGroup($builderName);

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

    public function andWhere()
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

    public function andWhereNot()
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

    public function startAndWhereGroup()
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

    public function startAndWhereNotGroup()
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

    public function _not()
    {
        return $this->addCondition(func_get_args(), 'and', true);
    }

    public function andNot()
    {
        return $this->addCondition(func_get_args(), 'and', true);
    }

    public function orNot()
    {
        return $this->addCondition(func_get_args(), 'or', true);
    }

    public function xorNot()
    {
        return $this->addCondition(func_get_args(), 'xor', true);
    }

    public function startGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    public function startAndGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    public function startOrGroup()
    {
        return $this->startConditionGroup('or', false);
    }

    public function startXorGroup()
    {
        return $this->startConditionGroup('xor', false);
    }

    public function startNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    public function startAndNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    public function startOrNotGroup()
    {
        return $this->startConditionGroup('or', true);
    }

    public function startXorNotGroup()
    {
        return $this->startConditionGroup('xor', true);
    }

    public function endGroup()
    {
        return $this->endConditionGroup();
    }
}
