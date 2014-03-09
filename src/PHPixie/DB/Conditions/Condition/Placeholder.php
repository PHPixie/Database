<?php

namespace PHPixie\DB\Conditions\Condition;

class Placeholder extends \PHPixie\DB\Conditions\Condition
{
    protected $builder;

    public function __construct($builder)
    {
        $this->builder = $builder;
    }
    
    public function builder() 
    {
        return $this->builder;
    }
    
    public function conditions()
    {
    }
}
