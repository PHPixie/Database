<?php

namespace PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded;

abstract class Group extends \PHPixie\Database\Conditions\Condition\Collection\Group
                     implements \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded
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