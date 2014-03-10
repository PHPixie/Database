<?php

namespace PHPixie\DB;

abstract class Query
{
    protected $db;
    protected $conditions;
    protected $connection;
    protected $parser;
    protected $config;
    protected $type;

    protected $data = null;
    protected $fields = array();
    protected $limit;
    protected $offset;
    protected $orderBy = array();
    protected $conditionBuilders = array();
    protected $lastUsedBuilder;

    public function __construct($db, $conditions, $connection, $parser, $config, $type)
    {
        $this->db         = $db;
        $this->conditions = $conditions;
        $this->connection = $connection;
        $this->parser     = $parser;
        $this->config     = $config;
        $this->type($type);
    }

    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function fields($fields)
    {
        if ($fields !== null && !is_array($fields))
            throw new \PHPixie\DB\Exception\Builder("Field list must either be an array or NULL");

        $this->fields = $fields;

        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function limit($limit)
    {
        if (!is_numeric($limit))
            throw new \PHPixie\DB\Exception\Builder("Limit must be a number");

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
            throw new \PHPixie\DB\Exception\Builder("Offset must be a number");

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
            throw new \PHPixie\DB\Exception\Builder("Order direction must be either 'asc' or  'desc'");

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
            if ($this->lastUsedBuilder === null)
                throw new \PHPixie\DB\Exception\Builder("None of the condition builders were used");

            return $this->lastUsedBuilder;
        }

        if (!isset($this->conditionBuilders[$name]))
            $this->conditionBuilders[$name] = $this->conditions->builder();

        $this->lastUsedBuilder = $this->conditionBuilders[$name];

        return $this->lastUsedBuilder;
    }

    protected function getConditions($name)
    {
        if (!isset($this->conditionBuilders[$name]))
            return array();

        return $this->conditionBuilders[$name]->getConditions();
    }

    protected function addCondition($args, $logic = 'and', $negate = false, $builderName = null)
    {
        $this->conditionBuilder($builderName)->addCondition($logic, $negate, $args);

        return $this;
    }

    protected function startConditionGroup($logic = 'and', $builderName = null)
    {
        $this->conditionBuilder($builderName)->startGroup($logic);

        return $this;
    }

    protected function endConditionGroup($builderName = null)
    {
        $this->conditionBuilder($builderName)->endGroup();

        return $this;
    }

    public function getWhereBuilder() {
        return $this->conditionBuilder('where');
    }
    
    public function getWhereConditions() {
        return $this->getConditions('where');
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
        return $this->startConditionGroup($logic);
    }

    public function endGroup()
    {
        return $this->endConditionGroup();
    }

    public function parse()
    {
        return $this->parser->parse($this);
    }

    abstract public function execute();

}
