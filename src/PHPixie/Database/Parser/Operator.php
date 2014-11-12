<?php

namespace PHPixie\Database\Parser;

abstract class Operator
{
    protected $methodMap = array();
    protected $operators;

    public function __construct()
    {
        $this->buildMethodMap();
    }

    public function parse($condition)
    {
        $operator = $condition->operator;
        if(!isset($this->methodMap[$operator]))
            throw new \PHPixie\Database\Exception\Parser("The '{$operator}' operator is not supported");

        $method = $this->methodMap[$operator];
        $field = $condition->field;
        $values = $condition->values;
        $negated = $condition->negated();

        return call_user_func(array($this, 'parse'.ucfirst($method)), $field, $operator, $values, $negated);
    }

    protected function buildMethodMap()
    {
        foreach($this->operators as $method => $operators)
            foreach($operators as $operator)
                $this->methodMap[$operator] = $method;
    }
}
