<?php

namespace PHPixie\Database\Conditions\Condition\Field;

abstract class Implementation extends    \PHPixie\Database\Conditions\Condition\Implementation
                              implements \PHPixie\Database\Conditions\Condition\Field
{
    protected $field;
    
    protected function __construct($field)
    {
        $this->field = $field;
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
}