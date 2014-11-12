<?php

namespace PHPixie\Database\Values;

class OrderBy
{
    protected $field;
    protected $direction;
    
    public function __construct($field, $direction)
    {
        if(!in_array($direction, array('asc', 'desc')))
            throw new \PHPixie\Database\Exception\Value("Invalid order direction '$direction'");
        
        $this->field = $field;
        $this->direction = $direction;
    }
    
    public function field()
    {
        return $this->field;
    }
    
    public function direction()
    {
        return $this->direction;
    }
}