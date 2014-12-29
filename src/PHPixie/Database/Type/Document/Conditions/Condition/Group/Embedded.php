<?php

namespace PHPixie\Database\Type\Document\Conditions\Condition\Group;

abstract class Embedded extends \PHPixie\Database\Conditions\Condition\Group
{
    protected $field;
    
    public function __construct($field)
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
    }
}