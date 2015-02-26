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
        $operator = $condition->operator();
        $method = $this->getMethodName($operator);
        
        return $this->$method(
            $condition->field(),
            $operator,
            $condition->values(),
            $condition->isNegated()
        );
    }
    
    protected function getMethodName($operator)
    {
        if(!isset($this->methodMap[$operator]))
            throw new \PHPixie\Database\Exception\Parser("The '{$operator}' operator is not supported");
        
        return 'parse'.ucfirst($this->methodMap[$operator]);
    }

    protected function buildMethodMap()
    {
        foreach($this->operators as $method => $operators)
            foreach($operators as $operator)
                $this->methodMap[$operator] = $method;
    }
}
