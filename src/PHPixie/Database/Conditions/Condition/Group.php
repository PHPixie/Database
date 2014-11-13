<?php

namespace PHPixie\Database\Conditions\Condition;

class Group extends \PHPixie\Database\Conditions\Condition
{
    protected $conditions = array();
    
    public function add($condition)
    {
        $this->conditions[] = $condition;
    }

    public function conditions()
    {
        return $this->conditions;
    }

    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }
}
