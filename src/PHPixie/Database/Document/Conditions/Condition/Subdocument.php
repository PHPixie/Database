<?php

namespace PHPixie\Database\Document\Conditions;

class Subdocument extends \PHPixie\Database\Conditions\Condition
{
    protected $field;
    protected $subdocument;
    
    public function __construct($field, $subdocument)
    {
        $this->field = $fielld;
        $this->subdocument = $subdocument;
    }
    
    public function subdocument()
    {
        return $this->subdocument;
    }
}