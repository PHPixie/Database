<?php

namespace PHPixie\Database\Conditions\Condition;

class Group extends \PHPixie\Database\Conditions\Condition
{
    protected $conditions = array();
    protected $allowedLogic = array('and', 'or', 'xor');

    public function addAnd($condition)
    {
        $this->add($condition, 'and');
    }

    public function addOr($condition)
    {
        $this->add($condition, 'or');
    }

    public function addXor($condition)
    {
        $this->add($condition, 'xor');
    }

    public function add($condition, $logic = 'and')
    {
        if (!in_array($logic, $this->allowedLogic))
            throw new \PHPixie\Database\Exception("The '$logic' logic is not supported");

        $condition->logic = $logic;
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
