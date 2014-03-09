<?php

namespace PHPixie\DB;

class Conditions
{
    public function placeholder($defaultOperator = '=')
    {
        $builder = $this->builder($defaultOperator);
        return new Conditions\Condition\Placeholder($builder);
    }

    public function operator($field, $operator, $values)
    {
        return new Conditions\Condition\Operator($field, $operator, $values);
    }

    public function group()
    {
        return new Conditions\Condition\Group();
    }

    public function builder($defaultOperator = '=')
    {
        return new Conditions\Builder($this, $defaultOperator);
    }
}
