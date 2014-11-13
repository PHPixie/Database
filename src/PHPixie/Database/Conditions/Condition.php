<?php

namespace PHPixie\Database\Conditions;

abstract class Condition
{
    protected $allowedLogic = array('and', 'or', 'xor');
    
    protected $negated = false;
    protected $logic = 'and';

    public function logic()
    {
        return $this->logic;
    }

    public function setLogic($logic)
    {
        if(!in_array($logic, $this->allowedLogic))
            throw new \PHPixie\Database\Exception\Builder("The '$logic' logic is not supported");
        
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
