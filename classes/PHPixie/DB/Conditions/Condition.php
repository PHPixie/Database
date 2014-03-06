<?php

namespace PHPixie\DB\Conditions;

abstract class Condition
{
    protected $negated = false;
    public $logic;

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
