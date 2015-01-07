<?php

namespace PHPixie\Database\Type\Document\Conditions\Condition\Field;

class In extends \PHPixie\Database\Conditions\Condition\Implementation
         implements \PHPixie\Database\Conditions\Condition\Field
{
    protected $field;
    protected $values;
    
    public function __construct($field, $values)
    {
        $this->field = $field;
        $this->values = $values;
    }
    
    public function field()
    {
        return $this->field;
    }
    
    public function setField()
    {
        $this->field = $field;
        return $this;
    }
    
    public function values()
    {
        return $this->values;
    }
    
    public function setValues()
    {
        $this->values = $values;
        return $this;
    }
}