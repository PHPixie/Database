<?php

namespace PHPixie\Database\Query\Data;

class Values
{
    protected $values;
    
    public function __construct($values)
    {
        $this->values = $values;
    }
    
    public function values()
    {
        return $this->values;
    }
    
}