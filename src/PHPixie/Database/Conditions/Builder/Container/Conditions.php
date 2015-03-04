<?php

namespace PHPixie\Database\Conditions\Builder\Container;

abstract class Conditions extends \PHPixie\Database\Conditions\Builder\Container
{
    protected $conditions;
    
    public function __construct($conditions, $defaultOperator = '=')
    {
        $this->conditions = $conditions;
        parent::__construct($defaultOperator);
    }
    
    protected function buildGroupCondition()
    {
        return $this->conditions->group();
    }
    
    protected function buildOperatorCondition($field, $operator, $values)
    {
        return $this->conditions->operator($field, $operator, $values);
    }
    
    protected function buildPlaceholderCondition($allowEmpty)
    {
        return $this->conditions->placeholder($this->defaultOperator, $allowEmpty);
    }
}