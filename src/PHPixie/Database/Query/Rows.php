<?php

namespace PHPixie\Database\Query;

class Rows
{
	protected $conditions;
    protected $limit;
    protected $offset;
    protected $orderBy = array();
    protected $conditionBuilders = array();
    protected $defaultBuilder;

	
	public function __construct($conditions)
	{
		$this->conditions = $conditions;
	}
	
    public function limit($limit)
    {
        if (!is_numeric($limit))
            throw new \PHPixie\Database\Exception\Builder("Limit must be a number");

        $this->limit = $limit;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function offset($offset)
    {
        if (!is_numeric($offset))
            throw new \PHPixie\Database\Exception\Builder("Offset must be a number");

        $this->offset = $offset;

        return $this;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function orderBy($field, $dir = 'asc')
    {
        if ($dir !== 'asc' && $dir !== 'desc')
            throw new \PHPixie\Database\Exception\Builder("Order direction must be either 'asc' or  'desc'");

        $this->orderBy[] = array($field, $dir);

        return $this;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    protected function conditionBuilder($name = null)
    {
        if ($name === null) {
            if ($this->defaultBuilder === null)
                throw new \PHPixie\Database\Exception\Builder("None of the condition builders were used");

            return $this->defaultBuilder;
        }

        if (!isset($this->conditionBuilders[$name]))
            $this->conditionBuilders[$name] = $this->conditions->builder();

        $this->defaultBuilder = $this->conditionBuilders[$name];

        return $this->defaultBuilder;
    }

    public function getConditions($name)
    {
        if (!isset($this->conditionBuilders[$name]))
            return array();

        return $this->conditionBuilders[$name]->getConditions();
    }

    public function addCondition($args, $logic = 'and', $negate = false, $builderName = null)
    {
        $this->conditionBuilder($builderName)->addCondition($logic, $negate, $args);
    }

    public function startConditionGroup($logic = 'and', $builderName = null)
    {
        $this->conditionBuilder($builderName)->startGroup($logic);

    }

    public function endConditionGroup($builderName = null)
    {
        $this->conditionBuilder($builderName)->endGroup();
    }
    
    public function defaultBuilder()
    {
		return $this->defultBuilder;
	}
	
	public function setDefaultBuilder($builder)
	{
		$this->defaultBuilder = $builder;
	}

}
