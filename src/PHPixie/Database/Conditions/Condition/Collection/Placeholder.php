<?php

namespace PHPixie\Database\Conditions\Condition\Collection;

class Placeholder extends \PHPixie\Database\Conditions\Condition\Implementation
                  implements \PHPixie\Database\Conditions\Condition\Collection
{
    protected $container;
    protected $allowEmpty;

    public function __construct($container, $allowEmpty = true)
    {
        $this->container      = $container;
        $this->allowEmpty     = $allowEmpty;
    }

    public function container()
    {
        return $this->container;
    }

    public function conditions()
    {
        $conditions = $this->container->getConditions();

        if (empty($conditions))
            if(!$this->allowEmpty)
                throw new \PHPixie\Database\Exception\Builder("This placeholder cannot be empty");

        return $conditions;
    }
}
