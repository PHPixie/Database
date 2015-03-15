<?php

namespace PHPixie\Database\Conditions\Condition\Field;

class Operator extends    Implementation
               implements \PHPixie\Database\Conditions\Condition\Field
{
    protected $operator;
    protected $values;
    
    public function __construct($field, $operator, $values)
    {
        parent::__construct($field);
        $this->operator = $operator;
        $this->values = $values;
    }
    
    public function operator()
    {
        return $this->operator;
    }
    
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }
    
    public function values()
    {
        return $this->values;
    }
    
    public function setValues($values)
    {
        $this->values = $values;
        return $this;
    }
}