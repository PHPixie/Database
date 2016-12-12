<?php

namespace PHPixie\Database\Driver\PDO\Query;

abstract class Items extends \PHPixie\Database\Driver\PDO\Query
                     implements \PHPixie\Database\Type\SQL\Query\Items,
                                \PHPixie\Database\Driver\PDO\Conditions\Builder
{

    /**
     * Items constructor.
     *
     * @param $connection
     * @param $parser
     * @param $builder
     */
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

    /**
     * @param $limit
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->builder->setLimit($limit);

        return $this;
    }

    /**
     * @return $this
     */
    public function clearLimit()
    {
        $this->builder->clearValue('limit');

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->builder->getValue('limit');
    }

    /**
     * @param $offset
     *
     * @return $this
     */
    public function offset($offset)
    {
        $this->builder->setOffset($offset);

        return $this;
    }

    /**
     * @return $this
     */
    public function clearOffset()
    {
        $this->builder->clearValue('offset');

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->builder->getValue('offset');
    }

    /**
     * @param $field
     * @param $direction
     *
     * @return $this
     */
    public function orderBy($field, $direction)
    {
        $this->builder->addOrderBy($field, $direction);

        return $this;
    }

    /**
     * @param $field
     *
     * @return $this
     */
    public function orderAscendingBy($field)
    {
        $this->builder->addOrderAscendingBy($field);

        return $this;
    }

    /**
     * @param $field
     *
     * @return $this
     */
    public function orderDescendingBy($field)
    {
        $this->builder->addOrderDescendingBy($field);

        return $this;
    }

    /**
     * @return $this
     */
    public function clearOrderBy()
    {
        $this->builder->clearArray('orderBy');

        return $this;
    }

    /**
     * @return array
     */
    public function getOrderBy()
    {
        return $this->builder->getArray('orderBy');
    }

    /**
     * @param        $table
     * @param null   $alias
     * @param string $type
     *
     * @return $this
     */
    public function join($table, $alias = null, $type = 'inner')
    {
        $this->builder->addJoin($table, $alias, $type);

        return $this;
    }

    /**
     * @return $this
     */
    public function clearJoins()
    {
        $this->builder->clearArray('joins');

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJoins()
    {
        return $this->builder->getArray('joins');
    }


    /**
     * @param      $logic
     * @param      $negate
     * @param      $field
     * @param      $operator
     * @param      $values
     * @param null $containerName
     *
     * @return $this
     */
    protected function addContainerOperatorCondition($logic, $negate, $field, $operator, $values, $containerName = null)
    {
        $this->builder->addOperatorCondition($logic, $negate, $field, $operator, $values, $containerName);
        
        return $this;
    }

    /**
     * @param      $field
     * @param      $values
     * @param      $logic
     * @param      $negate
     * @param null $containerName
     *
     * @return $this
     */
    protected function addContainerInOperatorCondition($field, $values, $logic, $negate, $containerName = null)
    {
        $this->builder->addInOperatorCondition($field, $values, $logic, $negate, $containerName);

        return $this;
    }

    /**
     * @param      $logic
     * @param      $negate
     * @param      $args
     * @param null $containerName
     *
     * @return $this
     */
    protected function buildContainerCondition($logic, $negate, $args, $containerName = null)
    {
        $this->builder->buildCondition($logic, $negate, $args, $containerName);

        return $this;
    }

    /**
     * @param      $logic
     * @param      $negate
     * @param      $condition
     * @param null $containerName
     *
     * @return $this
     */
    protected function addContainerCondition($logic, $negate, $condition, $containerName = null)
    {
        $this->builder->addCondition($logic, $negate, $condition, $containerName);

        return $this;
    }

    /**
     * @param string $logic
     * @param bool   $negate
     * @param null   $containerName
     *
     * @return $this
     */
    protected function startContainerConditionGroup($logic = 'and', $negate = false, $containerName = null)
    {
        $this->builder->startConditionGroup($logic, $negate, $containerName);

        return $this;
    }

    /**
     * @param null $containerName
     *
     * @return $this
     */
    protected function endContainerConditionGroup($containerName = null)
    {
        $this->builder->endConditionGroup($containerName);

        return $this;
    }

    /**
     * @param string $logic
     * @param bool   $negate
     * @param bool   $allowEmpty
     * @param null   $containerName
     *
     * @return mixed
     */
    protected function addContainerPlaceholder($logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return $this->builder->addPlaceholder($logic, $negate, $allowEmpty, $containerName);
    }

    /**
     * @param $logic
     * @param $negate
     * @param $args
     *
     * @return $this
     */
    public function buildCondition($logic, $negate, $args)
    {
        $this->builder->buildCondition($logic, $negate, $args);
        return $this;
    }


    /**
     * @param $logic
     * @param $negate
     * @param $condition
     *
     * @return $this
     */
    public function addCondition($logic, $negate, $condition)
    {
        $this->builder->addCondition($logic, $negate, $condition);
        return $this;
    }

    /**
     * @param $logic
     * @param $negate
     * @param $field
     * @param $operator
     * @param $values
     *
     * @return static
     */
    public function addOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        return $this->addContainerOperatorCondition($logic, $negate, $field, $operator, $values);
    }

    /**
     * @param        $field
     * @param        $values
     * @param string $logic
     * @param bool   $negate
     *
     * @return static
     */
    public function addInOperatorCondition($field, $values, $logic = 'and', $negate = false)
    {
        return $this->addContainerInOperatorCondition($field, $values, $logic, $negate);
    }

    /**
     * @param        $field
     * @param        $values
     * @param string $logic
     * @param bool   $negate
     *
     * @return static
     */
    public function addWhereInOperatorCondition($field, $values, $logic = 'and', $negate = false)
    {
        return $this->addContainerInOperatorCondition($field, $values, $logic, $negate, 'where');
    }

    /**
     * @param string $logic
     * @param bool   $negate
     *
     * @return static
     */
    public function startConditionGroup($logic = 'and', $negate = false)
    {
        return $this->startContainerConditionGroup($logic, $negate);
    }

    /**
     * @param string $logic
     * @param bool   $negate
     * @param bool   $allowEmpty
     *
     * @return mixed
     */
    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerPlaceholder($logic, $negate, $allowEmpty);
    }

    /**
     * @return $this
     */
    protected function endOnConditionGroup()
    {
        $this->builder->endOnConditionGroup();

        return $this;
    }


    /**
     * @param $logic
     * @param $negate
     * @param $condition
     *
     * @return $this
     */
    public function addOnCondition($logic, $negate, $condition)
    {
        $this->builder->addOnCondition($logic, $negate, $condition);

        return $this;
    }

    /**
     * @param        $field
     * @param        $values
     * @param string $logic
     * @param bool   $negate
     *
     * @return $this
     */
    public function addOnInOperatorCondition($field, $values, $logic = 'and', $negate = false)
    {
        $this->builder->addOnInOperatorCondition($field, $values, $logic, $negate);
        
        return $this;
    }

    /**
     * @param $logic
     * @param $negate
     * @param $args
     *
     * @return $this
     */
    public function buildOnCondition($logic, $negate, $args)
    {
        $this->builder->buildOnCondition($logic, $negate, $args);

        return $this;
    }


    /**
     * @param $logic
     * @param $negate
     * @param $field
     * @param $operator
     * @param $values
     *
     * @return $this
     */
    public function addOnOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        $this->builder->addOnOperatorCondition($logic, $negate, $field, $operator, $values);
        
        return $this;
    }

    /**
     * @param string $logic
     * @param bool   $negate
     * @param bool   $allowEmpty
     *
     * @return mixed
     */
    public function addOnPlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->builder->addOnPlaceholder($logic, $negate, $allowEmpty);
    }

    /**
     * @param string $logic
     * @param bool   $negate
     *
     * @return $this
     */
    public function startOnConditionGroup($logic = 'and', $negate = false)
    {
        $this->builder->startOnConditionGroup($logic, $negate);

        return $this;
    }

    /**
     * @param $logic
     * @param $negate
     * @param $condition
     *
     * @return static
     */
    public function addWhereCondition($logic, $negate, $condition)
    {
        return $this->addContainerCondition($logic, $negate, $condition, 'where');
    }

    /**
     * @param $logic
     * @param $negate
     * @param $params
     *
     * @return static
     */
    public function buildWhereCondition($logic, $negate, $params)
    {
        return $this->buildContainerCondition($logic, $negate, $params, 'where');
    }


    /**
     * @return mixed
     */
    public function getWhereContainer()
    {
        return $this->builder->conditionContainer('where');
    }

    /**
     * @return mixed
     */
    public function getWhereConditions()
    {
        return $this->builder->getConditions('where');
    }

    /**
     * @param $logic
     * @param $negate
     * @param $field
     * @param $operator
     * @param $values
     *
     * @return static
     */
    public function addWhereOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        return $this->addContainerOperatorCondition($logic, $negate, $field, $operator, $values, 'where');
    }

    /**
     * @param string $logic
     * @param bool   $negate
     *
     * @return static
     */
    public function startWhereConditionGroup($logic = 'and', $negate = false)
    {
        return $this->startContainerConditionGroup($logic, $negate, 'where');
    }

    /**
     * @param string $logic
     * @param bool   $negate
     * @param bool   $allowEmpty
     *
     * @return mixed
     */
    public function addWherePlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addContainerPlaceholder($logic, $negate, $allowEmpty, 'where');
    }

    /**
     * @return static
     */
    public function where()
    {
        return $this->buildContainerCondition('and', false, func_get_args(), 'where');
    }

    /**
     * @return static
     */
    public function andWhere()
    {
        return $this->buildContainerCondition('and', false, func_get_args(), 'where');
    }

    /**
     * @return static
     */
    public function orWhere()
    {
        return $this->buildContainerCondition('or', false, func_get_args(), 'where');
    }

    /**
     * @return static
     */
    public function xorWhere()
    {
        return $this->buildContainerCondition('xor', false, func_get_args(), 'where');
    }

    /**
     * @return static
     */
    public function whereNot()
    {
        return $this->buildContainerCondition('and', true, func_get_args(), 'where');
    }

    /**
     * @return static
     */
    public function andWhereNot()
    {
        return $this->buildContainerCondition('and', true, func_get_args(), 'where');
    }

    /**
     * @return static
     */
    public function orWhereNot()
    {
        return $this->buildContainerCondition('or', true, func_get_args(), 'where');
    }

    /**
     * @return static
     */
    public function xorWhereNot()
    {
        return $this->buildContainerCondition('xor', true, func_get_args(), 'where');
    }

    /**
     * @return static
     */
    public function startWhereGroup()
    {
        return $this->startContainerConditionGroup('and', false, 'where');
    }

    /**
     * @return static
     */
    public function startAndWhereGroup()
    {
        return $this->startContainerConditionGroup('and', false, 'where');
    }

    /**
     * @return static
     */
    public function startOrWhereGroup()
    {
        return $this->startContainerConditionGroup('or', false, 'where');
    }

    /**
     * @return static
     */
    public function startXorWhereGroup()
    {
        return $this->startContainerConditionGroup('xor', false, 'where');
    }

    /**
     * @return static
     */
    public function startWhereNotGroup()
    {
        return $this->startContainerConditionGroup('and', true, 'where');
    }

    /**
     * @return static
     */
    public function startAndWhereNotGroup()
    {
        return $this->startContainerConditionGroup('and', true, 'where');
    }

    /**
     * @return static
     */
    public function startOrWhereNotGroup()
    {
        return $this->startContainerConditionGroup('or', true, 'where');
    }

    /**
     * @return static
     */
    public function startXorWhereNotGroup()
    {
        return $this->startContainerConditionGroup('xor', true, 'where');
    }

    /**
     * @return static
     */
    public function endWhereGroup()
    {
        return $this->endContainerConditionGroup('where');
    }


    /**
     * @return static
     */
    public function _and()
    {
        return $this->buildContainerCondition('and', false, func_get_args());
    }

    /**
     * @return static
     */
    public function _or()
    {
        return $this->buildContainerCondition('or', false, func_get_args());
    }

    /**
     * @return static
     */
    public function _xor()
    {
        return $this->buildContainerCondition('xor', false, func_get_args());
    }

    /**
     * @return static
     */
    public function _not()
    {
        return $this->buildContainerCondition('and', true, func_get_args());
    }

    /**
     * @return static
     */
    public function andNot()
    {
        return $this->buildContainerCondition('and', true, func_get_args());
    }

    /**
     * @return static
     */
    public function orNot()
    {
        return $this->buildContainerCondition('or', true, func_get_args());
    }

    /**
     * @return static
     */
    public function xorNot()
    {
        return $this->buildContainerCondition('xor', true, func_get_args());
    }

    /**
     * @return static
     */
    public function startGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    /**
     * @return static
     */
    public function startAndGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    /**
     * @return static
     */
    public function startOrGroup()
    {
        return $this->startConditionGroup('or', false);
    }

    /**
     * @return static
     */
    public function startXorGroup()
    {
        return $this->startConditionGroup('xor', false);
    }

    /**
     * @return static
     */
    public function startNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    /**
     * @return static
     */
    public function startAndNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    /**
     * @return static
     */
    public function startOrNotGroup()
    {
        return $this->startConditionGroup('or', true);
    }

    /**
     * @return static
     */
    public function startXorNotGroup()
    {
        return $this->startConditionGroup('xor', true);
    }

    /**
     * @return static
     */
    public function endGroup()
    {
        return $this->endContainerConditionGroup();
    }


    /**
     * @return static
     */
    public function on()
    {
        return $this->buildOnCondition('and', false, func_get_args());
    }

    /**
     * @return static
     */
    public function andOn()
    {
        return $this->buildOnCondition('and', false, func_get_args());
    }

    /**
     * @return static
     */
    public function orOn()
    {
        return $this->buildOnCondition('or', false, func_get_args());
    }

    /**
     * @return static
     */
    public function xorOn()
    {
        return $this->buildOnCondition('xor', false, func_get_args());
    }

    /**
     * @return static
     */
    public function onNot()
    {
        return $this->buildOnCondition('and', true, func_get_args());
    }

    /**
     * @return static
     */
    public function andOnNot()
    {
        return $this->buildOnCondition('and', true, func_get_args());
    }

    /**
     * @return static
     */
    public function orOnNot()
    {
        return $this->buildOnCondition('or', true, func_get_args());
    }

    /**
     * @return static
     */
    public function xorOnNot()
    {
        return $this->buildOnCondition('xor', true, func_get_args());
    }

    /**
     * @return static
     */
    public function startOnGroup()
    {
        return $this->startOnConditionGroup('and', false);
    }

    /**
     * @return static
     */
    public function startAndOnGroup()
    {
        return $this->startOnConditionGroup('and', false);
    }

    /**
     * @return static
     */
    public function startOrOnGroup()
    {
        return $this->startOnConditionGroup('or', false);
    }

    /**
     * @return static
     */
    public function startXorOnGroup()
    {
        return $this->startOnConditionGroup('xor', false);
    }

    /**
     * @return static
     */
    public function startOnNotGroup()
    {
        return $this->startOnConditionGroup('and', true);
    }

    /**
     * @return static
     */
    public function startAndOnNotGroup()
    {
        return $this->startOnConditionGroup('and', true);
    }

    /**
     * @return static
     */
    public function startOrOnNotGroup()
    {
        return $this->startOnConditionGroup('or', true);
    }

    /**
     * @return static
     */
    public function startXorOnNotGroup()
    {
        return $this->startOnConditionGroup('xor', true);
    }

    /**
     * @return static
     */
    public function endOnGroup()
    {
        return $this->endOnConditionGroup();
    }
}