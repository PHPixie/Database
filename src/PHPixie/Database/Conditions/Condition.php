<?php

namespace PHPixie\Database\Conditions;

abstract class Condition
{
    protected $negated = false;
    public $logic = 'and';

    public function logic()
    {
        return $this->logic;
    }
    
    public function setLogic($logic)
    {
        $this->logic = $logic;
        return $this;
    }
    
    public function negate()
    {
        $this->negated = !$this->negated;

        return $this;
    }

    public function negated()
    {
        return $this->negated;
    }

}
