<?php

namespace PHPixie\Database\Conditions\Condition;

class Placeholder extends \PHPixie\Database\Conditions\Condition
{
    protected $conditions;
    protected $defaultOperator;
    protected $allowEmpty;
    protected $container;

    public function __construct($conditions, $defaultOperator = '=', $allowEmpty = true)
    {
        $this->conditions      = $conditions;
        $this->defaultOperator = $defaultOperator;
        $this->allowEmpty      = $allowEmpty;
    }

    public function container()
    {
        if ($this->container === null)
            $this->container = $this->conditions->container($this->defaultOperator);

        return $this->container;
    }

    public function conditions()
    {
        $conditions = array();

        if ($this->container !== null)
            $conditions = $this->container->getConditions();

        if (empty($conditions))
            if(!$this->allowEmpty)
                throw new \PHPixie\Database\Exception\Builder("This placeholder cannot be empty");

        return $conditions;
    }
}
