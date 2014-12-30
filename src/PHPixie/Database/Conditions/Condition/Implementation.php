<?php

namespace PHPixie\Database\Conditions\Condition;

abstract class Implementation implements \PHPixie\Database\Conditions\Condition
{
    protected $allowedLogic = array('and', 'or', 'xor');
    
    protected $isNegated = false;
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
        $this->isNegated = !$this->isNegated;

        return $this;
    }

    public function isNegated()
    {
        return $this->isNegated;
    }
    
    public function setIsNegated($isNegated)
    {
       if($isNegated !== $this->isNegated())
           $this->negate();
        
        return $this;
    }

}
