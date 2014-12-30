<?php

namespace PHPixie\Database\Conditions\Condition\Collection;

class Group extends \PHPixie\Database\Conditions\Condition\Implementation
            implements \PHPixie\Database\Conditions\Condition\Collection
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
