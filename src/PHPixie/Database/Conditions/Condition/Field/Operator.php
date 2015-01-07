<?php

namespace PHPixie\Database\Conditions\Condition\Field;

class Operator extends \PHPixie\Database\Conditions\Condition\Implementation
               implements \PHPixie\Database\Conditions\Condition\Field
{
    protected $field;
    protected $operator;
    protected $values;
    
    public function __construct($field, $operator, $values)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->values = $values;
    }
    
    public function field()
    {
        return $this->field;
    }
    
    public function setField($field)
    {
        $this->field = $field;
        return $this;
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