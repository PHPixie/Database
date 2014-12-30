<?php

namespace PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded;

abstract class Placeholder extends \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Placeholder
                           implements \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded
{
    protected $field;
    
    public function __construct($container, $field, $allowEmpty = true)
    {
        $this->field = $field;
        parent::__construct($container, $allowEmpty);
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