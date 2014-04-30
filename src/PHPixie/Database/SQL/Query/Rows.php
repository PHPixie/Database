<?php

namespace PHPixie\Database\SQL\Query;

class Rows extends \PHPixie\Database\SQL\Query
{
	public function limit($limit)
    {
        $this->rows->limit($limit);
        return $this;
    }

    public function getLimit()
    {
        return $this->rows->getLimit();
    }

    public function offset($offset)
    {
        $this->rows->offset($offset);
        return $this;
    }

    public function getOffset()
    {
        return $this->rows->getOffset();
    }

    public function orderBy($field, $dir = 'asc')
    {
		$this->rows->orderBy($field, $dir);
        return $this;
    }

    public function getOrderBy()
    {
        return $this->rows->getOrderBy();
    }


    protected function addCondition($args, $logic = 'and', $negate = false, $builderName = null)
    {
        $this->rows->addCondition($args, $logic, $negate, $builderName);
        return $this;
    }

    protected function startConditionGroup($logic = 'and', $builderName = null)
    {
        $this->rows->startConditionGroup($logic, $builderName);
        return $this;
    }

    protected function endConditionGroup($builderName = null)
    {
        $this->rows->endGroup($builderName);
        return $this;
    }


    public function getWhereBuilder()
    {
        return $this->rows->conditionBuilder('where');
    }

    public function getWhereConditions()
    {
        return $this->rows->getConditions('where');
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

    public function startWhereGroup($logic = 'and')
    {
        return $this->startConditionGroup($logic, 'where');
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

    public function startGroup($logic='and')
    {
        $this->rows->startGroup($logic);
    }

    public function endGroup()
    {
        $this->rows->endGroup();
    }
    
    public function join($table, $alias = null, $type = 'inner')
    {
        $this->joins[] = array(
            'builder' => $this->rows->conditionBuilder('=*'),
            'table' => $table,
            'alias' => $alias,
            'type'  => $type
        );

        return $this;
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
        $this->rows->setDefaultBuilder($builder);

        return $builder;
    }

    protected function addOnCondition($args, $logic = 'and', $negate = false)
    {
        $this->lastOnBuilder()->addCondition($logic, $negate, $args);
        return $this;
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

    public function startOnGroup($logic = 'and')
    {
        $this->lastOnBuilder()->startGroup($logic);

        return $this;
    }

    public function endOnGroup()
    {
        $this->lastOnBuilder()->endGroup();

        return $this;
    }
	

}
