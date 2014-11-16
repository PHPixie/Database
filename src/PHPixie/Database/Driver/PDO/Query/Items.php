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

    
    
    
    protected function addContainerOperatorCondition($logic, $negate, $field, $operator, $values, $containerName = null)
    {
        $this->builder->addOperatorCondition($logic, $negate, $field, $operator, $values, $containerName);
        
        return $this;
    }
    
    protected function addContainerCondition($logic, $negate, $args, $containerName = null)
    {
        $this->builder->addCondition($logic, $negate, $args, $containerName);

        return $this;
    }

    protected function startContainerConditionGroup($logic = 'and', $negate = false, $containerName = null)
    {
        $this->builder->startConditionGroup($logic, $negate, $containerName);

        return $this;
    }

    protected function endContainerConditionGroup($containerName = null)
    {
        $this->builder->endConditionGroup($containerName);

        return $this;
    }
    
    protected function addContainerPlaceholder($logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        $this->builder->addPlaceholder($logic, $negate, $allowEmpty, $containerName);
        
        return $this;
    }
    
    public function addCondition($logic, $negate, $args)
    {
        $this->builder->addCondition($logic, $negate, $args);
        return $this;
    }
    
    public function addOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        return $this->addContainerOperatorCondition($logic, $negate, $field, $operator, $values);
    }
    
    public function startConditionGroup($logic = 'and', $negate = false)
    {
        return $this->startContainerConditionGroup($logic, $negate);
    }
    
    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerPlaceholder($logic, $negate, $allowEmpty);
    }
    
    protected function endOnConditionGroup()
    {
        $this->builder->endOnConditionGroup();

        return $this;
    }
    
    
    public function addOnCondition($logic, $negate, $args)
    {
        $this->builder->addOnCondition($logic, $negate, $args);

        return $this;
    }
    
    public function addOnOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        $this->builder->addOnOperatorCondition($logic, $negate, $field, $operator, $values);
        
        return $this;
    }
    
    public function addOnPlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        $this->builder->addOnPlaceholder($logic, $negate, $allowEmpty);
        
        return $this;
    }

    public function startOnConditionGroup($logic = 'and', $negate = false)
    {
        $this->builder->startOnConditionGroup($logic, $negate);

        return $this;
    }

    public function addWhereCondition($logic, $negate, $params)
    {
        return $this->addContainerCondition($logic, $negate, $params, 'where');
    }
    
    
    public function getWhereContainer()
    {
        return $this->builder->conditionContainer('where');
    }

    public function getWhereConditions()
    {
        return $this->builder->getConditions('where');
    }
    
    public function addWhereOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        return $this->addContainerOperatorCondition($logic, $negate, $field, $operator, $values, 'where');
    }

    public function startWhereConditionGroup($logic = 'and', $negate = false)
    {
        return $this->startContainerConditionGroup($logic, $negate, 'where');
    }

    public function addWherePlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerPlaceholder($logic, $negate, $allowEmpty, 'where');
    }

    public function where()
    {
        return $this->addContainerCondition('and', false, func_get_args(), 'where');
    }

    public function andWhere()
    {
        return $this->addContainerCondition('and', false, func_get_args(), 'where');
    }

    public function orWhere()
    {
        return $this->addContainerCondition('or', false, func_get_args(), 'where');
    }

    public function xorWhere()
    {
        return $this->addContainerCondition('xor', false, func_get_args(), 'where');
    }

    public function whereNot()
    {
        return $this->addContainerCondition('and', true, func_get_args(), 'where');
    }

    public function andWhereNot()
    {
        return $this->addContainerCondition('and', true, func_get_args(), 'where');
    }

    public function orWhereNot()
    {
        return $this->addContainerCondition('or', true, func_get_args(), 'where');
    }

    public function xorWhereNot()
    {
        return $this->addContainerCondition('xor', true, func_get_args(), 'where');
    }

    public function startWhereGroup()
    {
        return $this->startContainerConditionGroup('and', false, 'where');
    }

    public function startAndWhereGroup()
    {
        return $this->startContainerConditionGroup('and', false, 'where');
    }

    public function startOrWhereGroup()
    {
        return $this->startContainerConditionGroup('or', false, 'where');
    }

    public function startXorWhereGroup()
    {
        return $this->startContainerConditionGroup('xor', false, 'where');
    }

    public function startWhereNotGroup()
    {
        return $this->startContainerConditionGroup('and', true, 'where');
    }

    public function startAndWhereNotGroup()
    {
        return $this->startContainerConditionGroup('and', true, 'where');
    }

    public function startOrWhereNotGroup()
    {
        return $this->startContainerConditionGroup('or', true, 'where');
    }

    public function startXorWhereNotGroup()
    {
        return $this->startContainerConditionGroup('xor', true, 'where');
    }

    public function endWhereGroup()
    {
        return $this->endContainerConditionGroup('where');
    }
    
    
    public function _and()
    {
        return $this->addContainerCondition('and', false, func_get_args());
    }

    public function _or()
    {
        return $this->addContainerCondition('or', false, func_get_args());
    }

    public function _xor()
    {
        return $this->addContainerCondition('xor', false, func_get_args());
    }

    public function _not()
    {
        return $this->addContainerCondition('and', true, func_get_args());
    }

    public function andNot()
    {
        return $this->addContainerCondition('and', true, func_get_args());
    }

    public function orNot()
    {
        return $this->addContainerCondition('or', true, func_get_args());
    }

    public function xorNot()
    {
        return $this->addContainerCondition('xor', true, func_get_args());
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
        return $this->endContainerConditionGroup();
    }
    
    
    
    public function on()
    {
        return $this->addOnCondition('and', false, func_get_args());
    }

    public function andOn()
    {
        return $this->addOnCondition('and', false, func_get_args());
    }

    public function orOn()
    {
        return $this->addOnCondition('or', false, func_get_args());
    }

    public function xorOn()
    {
        return $this->addOnCondition('xor', false, func_get_args());
    }

    public function onNot()
    {
        return $this->addOnCondition('and', true, func_get_args());
    }

    public function andOnNot()
    {
        return $this->addOnCondition('and', true, func_get_args());
    }

    public function orOnNot()
    {
        return $this->addOnCondition('or', true, func_get_args());
    }

    public function xorOnNot()
    {
        return $this->addOnCondition('xor', true, func_get_args());
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
        return $this->endOnConditionGroup();
    }
}