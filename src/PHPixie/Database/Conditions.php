<?php

namespace PHPixie\Database;

class Conditions implements \PHPixie\Database\Conditions\Builder\Container\Builder
{
    public function placeholder($defaultOperator = '=', $allowEmpty = true)
    {
        $container = $this->container($defaultOperator);
        return new Conditions\Condition\Collection\Placeholder($container, $allowEmpty);
    }

    public function operator($field, $operator, $values)
    {
        return new Conditions\Condition\Field\Operator($field, $operator, $values);
    }

    public function group()
    {
        return new Conditions\Condition\Collection\Group();
    }

    public function container($defaultOperator = '=')
    {
        return new Conditions\Builder\Container($this, $defaultOperator);
    }
}
