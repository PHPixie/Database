<?php

namespace PHPixie\Database\Driver\PDO\Query;

abstract class Items extends \PHPixie\Database\Driver\PDO\Query implements \PHPixie\Database\SQL\Query\Items
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

    public function limit($limit)
    {
        $this->builder->setLimit($limit);

        return $this;
    }

    public function clearLimit()
    {
        $this->builder->clearValue('limit');

        return $this;
    }

    public function getLimit()
    {
        return $this->builder->getValue('limit');
    }

    public function offset($offset)
    {
        $this->builder->setOffset($offset);

        return $this;
    }

    public function clearOffset()
    {
        $this->builder->clearValue('offset');

        return $this;
    }

    public function getOffset()
    {
        return $this->builder->getValue('offset');
    }

    public function orderAscendingBy($field)
    {
        $this->builder->addOrderAscendingBy($field);

        return $this;
    }

    public function orderDescendingBy($field)
    {
        $this->builder->addOrderDescendingBy($field);

        return $this;
    }

    public function clearOrderBy()
    {
        $this->builder->clearArray('orderBy');

        return $this;
    }

    public function getOrderBy()
    {
        return $this->builder->getArray('orderBy');
    }

    public function join($table, $alias = null, $type = 'inner')
    {
        $this->builder->addJoin($table, $alias, $type);

        return $this;
    }

    public function clearJoins()
    {
        $this->builder->clearArray('joins');

        return $this;
    }

    public function getJoins()
    {
        return $this->builder->getArray('joins');
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

    protected function addOnCondition($args, $logic = 'and', $negate = false)
    {
        $this->builder->addOnCondition($args, $logic, $negate);

        return $this;
    }

    protected function startOnConditionGroup($logic = 'and', $negate = false)
    {
        $this->builder->startOnConditionGroup($logic, $negate);

        return $this;
    }

    protected function endOnConditionGroup()
    {
        $this->builder->endOnConditionGroup();

        return $this;
    }

    public function on()
    {
        return $this->addOnCondition(func_get_args(), 'and', false);
    }

    public function andOn()
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

    public function andOnNot()
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

    public function startAndOnGroup()
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

    public function startOnNotGroup()
    {
        return $this->startOnConditionGroup('and', true);
    }

    public function startAndOnNotGroup()
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
        $this->endOnConditionGroup();

        return $this;
    }
}
