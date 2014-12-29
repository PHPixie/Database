<?php

namespace PHPixie\Database\Type\Document\Conditions\Condition\Placeholder;

class Subdocument extends \PHPixie\Database\Type\Document\Conditions\Condition\Placeholder
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
}